<?php

namespace App\Services\QueryEngine;

use App\Contracts\HasIconContract;
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
        $eagerLoad = $this->resolveEagerLoads($modelClass, $traverse, $fields);

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

    protected function resolveEagerLoads(string $modelClass, array $traverse, array $fields): array
    {
        $paths = collect($traverse);

        foreach ($fields as $field) {
            $parts = explode('.', $field);
            if (count($parts) > 1) {
                array_pop($parts);
                $paths->push(implode('.', $parts));
            }
        }

        // Convertir chaque path snake_case en noms de méthodes Eloquent réels
        // Ex : logical_servers.applications → logicalServers.applications
        return $paths->unique()
            ->map(fn ($path) => $this->resolveRelationPath($modelClass, $path))
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Résout un chemin de relation pointé en noms de méthodes Eloquent.
     * Ex : logical_servers.applications → logicalServers.applications
     * Chaque segment est résolu sur la classe liée du segment précédent.
     */
    protected function resolveRelationPath(string $modelClass, string $path): string
    {
        $parts    = explode('.', $path);
        $resolved = [];
        $class    = $modelClass;

        foreach ($parts as $segment) {
            $method     = QueryEngineIntrospector::resolveRelationMethod($class, $segment);
            $resolved[] = $method;

            // Avancer vers la classe liée pour le segment suivant
            $relDef = collect(QueryEngineIntrospector::getRelations($class))
                ->firstWhere('method', $method);
            if ($relDef) {
                $class = QueryEngineIntrospector::resolveModelClassFromAny($relDef['related']);
            }
        }

        return implode('.', $resolved);
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

        $field  = array_pop($parts);
        $method = $isOr ? 'orWhereHas' : 'whereHas';
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
        $relation   = array_shift($relations);
        $modelClass = get_class($builder->getModel());
        $relMethod  = QueryEngineIntrospector::resolveRelationMethod($modelClass, $relation);

        $builder->$method($relMethod, function (Builder $q) use ($relations, $field, $operator, $value) {
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

    protected function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof \Carbon\CarbonInterface) {
            return $value->toDateString();
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        if (is_array($value) && isset($value['date'])) {
            return substr($value['date'], 0, 10);
        }
        return $value;
    }

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
                        $relMethod = QueryEngineIntrospector::resolveRelationMethod(get_class($item), $firstSegment);
                        $related   = $item->$relMethod;
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
     * L'ordre des colonnes respecte l'ordre original de $fields.
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

        // Champs racine
        $rootRow    = [];
        $modelClass = get_class($item);
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
                $relMethod = QueryEngineIntrospector::resolveRelationMethod(get_class($item), $relation);
                $related   = $item->$relMethod;
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

        // ── Réordonner chaque ligne selon l'ordre original de $fields ──
        return array_map(function (array $row) use ($fields): array {
            $ordered = [];
            foreach ($fields as $key) {
                if (array_key_exists($key, $row)) {
                    $ordered[$key] = $row[$key];
                }
            }
            // Clés non déclarées dans $fields ajoutées en fin
            foreach ($row as $k => $v) {
                if (! array_key_exists($k, $ordered)) {
                    $ordered[$k] = $v;
                }
            }
            return $ordered;
        }, $result);
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
        if (! isset($this->visitedNodes[$nodeId])) {
            $this->visitedNodes[$nodeId] = true;
            $this->nodes[$nodeId]        = $this->buildNode($item, $nodeId, $modelName);
        }

        // Ne pas return même si déjà visité :
        // un même nœud peut être atteint via un chemin différent avec des enfants à traverser.

        if (empty($traverse)) {
            return;
        }

        // Regrouper les chemins par relation de premier niveau
        // Ex: ['logicalServers', 'logicalServers.applications']
        //  → ['logicalServers' => ['applications']]
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
            try {
                $relMethod = QueryEngineIntrospector::resolveRelationMethod(get_class($item), $relation);
                $related   = $item->$relMethod;
                if (! $related) continue;

                $related = $related instanceof Model ? collect([$related]) : $related;
                if ($related->isEmpty()) continue;

                $relatedModelName = class_basename(get_class($related->first()));

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
            'icon'  => $item instanceof HasIconContract ? $item->getIcon() : '/images/default.png',
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
        $slug = QueryEngineIntrospector::modelToApiName($modelName);
        return "/admin/{$slug}/{$id}";
    }
}