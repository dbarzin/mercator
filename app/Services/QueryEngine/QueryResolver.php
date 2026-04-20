<?php

namespace App\Services\QueryEngine;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class QueryResolver
{
    protected const ALLOWED_OPERATORS = ['=', '!=', '<', '>', '<=', '>=', 'like', 'not like', 'in', 'not in'];

    protected array $visitedNodes = [];
    protected array $nodes        = [];
    protected array $edges        = [];

    // ─────────────────────────────────────────────────────────────
    // Point d'entrée
    // ─────────────────────────────────────────────────────────────

    public function execute(array $dsl): GraphResult|ListResult
    {
        $modelClass = QueryEngineIntrospector::resolveModelClass($dsl['from']);
        $builder    = $modelClass::query();

        foreach ($dsl['filters'] ?? [] as $filter) {
            $this->applyFilter($builder, $filter);
        }

        $traverse  = $dsl['traverse'] ?? [];
        $fields    = $dsl['fields']   ?? [];
        $eagerLoad = $this->resolveEagerLoads($traverse, $fields);

        if (! empty($eagerLoad)) {
            $builder->with($eagerLoad);
        }

        $items = $builder->limit($dsl['limit'] ?? 100)->get();

        return match ($dsl['output'] ?? 'list') {
            'graph' => $this->buildGraph($items, $dsl['from'], $traverse),
            default => $this->buildList($items, $dsl),
        };
    }

    // ─────────────────────────────────────────────────────────────
    // Eager loads
    // ─────────────────────────────────────────────────────────────

    protected function resolveEagerLoads(array $traverse, array $fields): array
    {
        $paths = collect($traverse);

        foreach ($fields as $field) {
            $parts = explode('.', $field);
            if (count($parts) > 1) {
                array_pop($parts);
                $paths->push(implode('.', $parts));
            }
        }

        return $paths->unique()->sort()->values()->toArray();
    }

    // ─────────────────────────────────────────────────────────────
    // Filtres — supporte a, a.b, a.b.c et groupes
    // ─────────────────────────────────────────────────────────────

    protected function applyFilter(Builder $builder, array $filter): void
    {
        // Groupe : { boolean, group: [...] }
        if (array_key_exists('group', $filter)) {
            $boolean = strtolower($filter['boolean'] ?? 'and');

            if ($boolean === 'not') {
                $builder->whereNot(function (Builder $q) use ($filter) {
                    foreach ($filter['group'] as $sub) {
                        $this->applyFilter($q, $sub);
                    }
                });
                return;
            }

            $method = $boolean === 'or' ? 'orWhere' : 'where';
            $builder->$method(function (Builder $q) use ($filter) {
                foreach ($filter['group'] as $sub) {
                    $this->applyFilter($q, $sub);
                }
            });
            return;
        }

        // Condition simple
        $operator = $filter['operator'] ?? '=';
        $value    = $filter['value'] ?? null;
        $boolean  = strtolower($filter['boolean'] ?? 'and');
        $isOr     = $boolean === 'or';

        abort_if(
            ! in_array($operator, self::ALLOWED_OPERATORS, true),
            422,
            "Opérateur interdit : [{$operator}]"
        );

        $parts = array_map(
            fn ($p) => preg_replace('/[^a-zA-Z0-9_]/', '', $p),
            explode('.', $filter['field'])
        );

        if (count($parts) === 1) {
            $this->applyWhereOnBuilder($builder, $parts[0], $operator, $value, $isOr);
            return;
        }

        $field     = array_pop($parts);
        $method    = $isOr ? 'orWhereHas' : 'whereHas';
        $this->applyNestedWhereHas($builder, $parts, $field, $operator, $value, $method);
    }

    protected function applyNestedWhereHas(
        Builder $builder,
        array   $relations,
        string  $field,
        string  $operator,
        mixed   $value,
        string  $method = 'whereHas'
    ): void {
        $relation = array_shift($relations);

        $builder->$method($relation, function (Builder $q) use ($relations, $field, $operator, $value) {
            if (empty($relations)) {
                $this->applyWhereOnBuilder($q, $field, $operator, $value);
            } else {
                $this->applyNestedWhereHas($q, $relations, $field, $operator, $value);
            }
        });
    }

    protected function applyWhereOnBuilder(
        Builder $builder,
        string  $field,
        string  $operator,
        mixed   $value,
        bool    $isOr = false
    ): void {
        $modelClass = get_class($builder->getModel());
        QueryEngineIntrospector::validateField($modelClass, $field);

        if ($isOr) {
            match ($operator) {
                'in'     => $builder->orWhereIn($field, (array) $value),
                'not in' => $builder->orWhereNotIn($field, (array) $value),
                default  => $builder->orWhere($field, $operator, $value),
            };
        } else {
            match ($operator) {
                'in'     => $builder->whereIn($field, (array) $value),
                'not in' => $builder->whereNotIn($field, (array) $value),
                default  => $builder->where($field, $operator, $value),
            };
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Normalisation des valeurs
    // ─────────────────────────────────────────────────────────────

    /**
     * Normalise une valeur pour la sérialisation JSON.
     * Les dates Carbon sont converties en chaîne "YYYY-MM-DD".
     * Les datetimes sont converties en "YYYY-MM-DD HH:MM:SS".
     */
    protected function normalizeValue(mixed $value): mixed
    {
        // Objet Carbon / CarbonInterface
        if ($value instanceof \Carbon\CarbonInterface) {
            return $value->toDateString();
        }
        // Tout objet DateTimeInterface
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        // Carbon sérialisé en tableau : ['date' => '2024-01-15 00:00:00.000000', 'timezone' => '...']
        if (is_array($value) && isset($value['date'])) {
            return substr($value['date'], 0, 10);
        }
        return $value;
    }

    /**
     * Retourne tous les attributs d'un modèle normalisés.
     * Passe par getAttribute() pour déclencher les casts Eloquent,
     * puis normalise les objets Carbon résultants.
     */
    protected function getAttributes(Model $item): array
    {
        return collect(array_keys($item->getAttributes()))
            ->mapWithKeys(fn ($key) => [$key => $this->normalizeValue($item->getAttribute($key))])
            ->toArray();
    }

    // ─────────────────────────────────────────────────────────────
    // Liste — Option A : une ligne par combinaison
    // ─────────────────────────────────────────────────────────────

    protected function buildList(Collection $items, array $dsl): ListResult
    {
        $fields   = $dsl['fields']   ?? [];
        $traverse = $dsl['traverse'] ?? [];
        $select   = $dsl['select']   ?? [];
        $rows     = [];

        foreach ($items as $item) {
            if (! empty($fields)) {
                foreach ($this->expandRow($item, $fields) as $row) {
                    $rows[] = $row;
                }
            } else {
                $modelClass = get_class($item);
                $attrs      = $this->getAttributes($item);

                if ($select) {
                    foreach ($select as $f) {
                        QueryEngineIntrospector::validateField($modelClass, $f);
                    }
                    $row = collect($attrs)->only($select)->toArray();
                } else {
                    $row = collect($attrs)->except(['created_at', 'updated_at', 'deleted_at'])->toArray();
                }

                foreach ($traverse as $rel) {
                    $firstSegment = explode('.', $rel)[0];
                    try {
                        $related = $item->$firstSegment;
                        if (! $related) { $row[$firstSegment] = ''; continue; }
                        $related = $related instanceof Model ? collect([$related]) : $related;
                        $row[$firstSegment] = $related->pluck('name')->filter()->join(', ');
                    } catch (\Throwable) {
                        $row[$firstSegment] = '';
                    }
                }

                $rows[] = $row;
            }
        }

        return new ListResult(rows: collect($rows), columns: array_keys($rows[0] ?? []));
    }

    /**
     * Expansion récursive d'un objet en N lignes (produit cartésien).
     */
    protected function expandRow(Model $item, array $fields): array
    {
        $rootFields     = [];
        $relationFields = [];

        foreach ($fields as $field) {
            $parts = explode('.', $field, 2);
            if (count($parts) === 1) {
                $rootFields[] = $field;
            } else {
                $relationFields[$parts[0]][] = $parts[1];
            }
        }

        // Champs racine — via getAttribute pour les casts (dates, etc.)
        $rootRow      = [];
        $modelClass   = get_class($item);
        foreach ($rootFields as $f) {
            QueryEngineIntrospector::validateField($modelClass, $f);
            $rootRow[$f] = $this->normalizeValue($item->getAttribute($f));
        }

        if (empty($relationFields)) {
            return [$rootRow];
        }

        $result = [$rootRow];

        foreach ($relationFields as $relation => $subFields) {
            try {
                $related = $item->$relation;
            } catch (\Throwable) {
                continue;
            }

            $related = $related
                ? ($related instanceof Model ? collect([$related]) : $related)
                : collect();

            if ($related->isEmpty()) {
                foreach ($result as &$row) {
                    foreach ($subFields as $sf) {
                        $row[$relation . '.' . $sf] = null;
                    }
                }
                unset($row);
                continue;
            }

            $newResult = [];
            foreach ($result as $row) {
                foreach ($related as $relatedItem) {
                    foreach ($this->expandRow($relatedItem, $subFields) as $subRow) {
                        $newRow = $row;
                        foreach ($subRow as $k => $v) {
                            $newRow[$relation . '.' . $k] = $v;
                        }
                        $newResult[] = $newRow;
                    }
                }
            }
            $result = $newResult;
        }

        return $result;
    }

    // ─────────────────────────────────────────────────────────────
    // Graphe
    // ─────────────────────────────────────────────────────────────

    protected function buildGraph(
        Collection $items,
        string     $modelName,
        array      $traverse,
    ): GraphResult {
        $this->visitedNodes = [];
        $this->nodes        = [];
        $this->edges        = [];

        foreach ($items as $item) {
            $this->traverseNode($item, $modelName, $traverse, null);
        }

        return new GraphResult(
            nodes: collect(array_values($this->nodes)),
            edges: collect(array_values($this->edges))
                ->unique(fn ($e) => $e['from'] . '||' . $e['to'])
                ->values(),
        );
    }

    protected function traverseNode(
        Model   $item,
        string  $modelName,
        array   $traverse,
        ?string $parentNodeId
    ): void {
        $nodeId = $modelName . '_' . $item->getKey();

        if ($parentNodeId !== null && $parentNodeId !== $nodeId) {
            $this->edges[$parentNodeId . '||' . $nodeId] = ['from' => $parentNodeId, 'to' => $nodeId];
        }

        // Enregistrer le nœud seulement la première fois
        $alreadyVisited = isset($this->visitedNodes[$nodeId]);
        if (! $alreadyVisited) {
            $this->visitedNodes[$nodeId] = true;
            $this->nodes[$nodeId]        = $this->buildNode($item, $nodeId, $modelName);
        }

        // Ne pas return ici même si déjà visité :
        // un même nœud peut être atteint via un chemin différent qui a encore des enfants à traverser.

        if (empty($traverse)) {
            return;
        }

        // Regrouper les chemins traverse par relation de premier niveau
        // Ex: ['logicalServers', 'logicalServers.applications', 'logicalServers.networks']
        //  → ['logicalServers' => ['applications', 'networks']]
        $relationMap = [];
        foreach ($traverse as $traversePath) {
            $parts    = explode('.', $traversePath, 2);
            $relation = $parts[0];
            $subPath  = $parts[1] ?? null;

            if (! isset($relationMap[$relation])) {
                $relationMap[$relation] = [];
            }
            if ($subPath !== null) {
                $relationMap[$relation][] = $subPath;
            }
        }

        foreach ($relationMap as $relation => $subPaths) {
            if (! method_exists($item, $relation)) {
                continue;
            }

            try {
                $related = $item->$relation;
                if (! $related) continue;

                $related = $related instanceof Model ? collect([$related]) : $related;
                if ($related->isEmpty()) continue;

                $relatedModelName = class_basename(get_class($related->first()));

                // subPaths contient tous les chemins à continuer pour cette relation
                // Ex: pour logicalServers → ['applications', 'networks']
                foreach ($related as $relatedItem) {
                    $this->traverseNode($relatedItem, $relatedModelName, $subPaths, $nodeId);
                }
            } catch (\Throwable) {
                continue;
            }
        }
    }

    protected function buildNode(Model $item, string $nodeId, string $modelName): array
    {
        return [
            'id'    => $nodeId,
            'label' => $item->name ?? $item->getAttribute('label') ?? (string) $item->getKey(),
            'group' => $modelName,
            'icon'  => $item->getIcon(),
            'data'  => [
                'id'    => $item->getKey(),
                'name'  => $item->name ?? '',
                'model' => $modelName,
                'url'   => $this->resolveUrl($modelName, $item->getKey()),
            ],
        ];
    }

    protected function resolveUrl(string $modelName, int|string $id): string
    {
        $slug = \Illuminate\Support\Str::kebab($modelName) . 's';
        return "/admin/{$slug}/{$id}";
    }
}