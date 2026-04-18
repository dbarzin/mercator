<?php

namespace App\Services\QueryEngine;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class QueryResolver
{
    protected const ALLOWED_OPERATORS = ['=', '!=', '<', '>', '<=', '>=', 'like', 'in', 'not in'];

    // État de la traversée graphe
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
            'graph' => $this->buildGraph($items, $dsl['from'], $traverse, $dsl['depth'] ?? 2),
            default => $this->buildList($items, $dsl),
        };
    }

    // ─────────────────────────────────────────────────────────────
    // Résolution des eager loads
    // ─────────────────────────────────────────────────────────────

    /**
     * Combine traverse et fields pour produire les with() Eloquent.
     *
     * traverse: ["logicalServers.applications"]
     * fields:   ["name", "logicalServers.name", "logicalServers.applications.name"]
     * → with:   ["logicalServers.applications"]
     */
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
    // Filtres — supporte a, a.b, a.b.c
    // ─────────────────────────────────────────────────────────────

    protected function applyFilter(Builder $builder, array $filter): void
    {
        $operator = $filter['operator'] ?? '=';
        $value    = $filter['value'];

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
            $this->applyWhereOnBuilder($builder, $parts[0], $operator, $value);
            return;
        }

        // Dernier segment = colonne, reste = relations imbriquées
        $field     = array_pop($parts);
        $this->applyNestedWhereHas($builder, $parts, $field, $operator, $value);
    }

    protected function applyNestedWhereHas(
        Builder $builder,
        array   $relations,
        string  $field,
        string  $operator,
        mixed   $value
    ): void {
        $relation = array_shift($relations);

        $builder->whereHas($relation, function (Builder $q) use ($relations, $field, $operator, $value) {
            if (empty($relations)) {
                $this->applyWhereOnBuilder($q, $field, $operator, $value);
            } else {
                $this->applyNestedWhereHas($q, $relations, $field, $operator, $value);
            }
        });
    }

    protected function applyWhereOnBuilder(Builder $builder, string $field, string $operator, mixed $value): void
    {
        $table = $builder->getModel()->getTable();
        abort_if(
            ! Schema::hasColumn($table, $field),
            422,
            "Colonne inconnue : [{$field}] dans [{$table}]"
        );

        match ($operator) {
            'in'     => $builder->whereIn($field, (array) $value),
            'not in' => $builder->whereNotIn($field, (array) $value),
            default  => $builder->where($field, $operator, $value),
        };
    }

    // ─────────────────────────────────────────────────────────────
    // Liste — Option A : une ligne par combinaison (produit cartésien)
    // ─────────────────────────────────────────────────────────────

    protected function buildList(Collection $items, array $dsl): ListResult
    {
        $fields   = $dsl['fields']   ?? [];
        $traverse = $dsl['traverse'] ?? [];
        $select   = $dsl['select']   ?? [];

        $rows = [];

        foreach ($items as $item) {
            if (! empty($fields)) {
                foreach ($this->expandRow($item, $fields) as $row) {
                    $rows[] = $row;
                }
            } else {
                // Mode legacy : colonnes plates + relations concaténées
                $row = $select
                    ? collect($item->toArray())->only($select)->toArray()
                    : collect($item->toArray())
                        ->except(['created_at', 'updated_at', 'deleted_at'])
                        ->toArray();

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

        $columns = array_keys($rows[0] ?? []);

        return new ListResult(rows: collect($rows), columns: $columns);
    }

    /**
     * Expanse récursivement un objet en N lignes selon la liste de champs.
     *
     * fields = ["name", "logicalServers.name", "logicalServers.applications.name"]
     *
     * PhysicalServer(srv1)
     *  → ls1 → app1  ⟹  {name:srv1, logicalServers.name:ls1, logicalServers.applications.name:app1}
     *  → ls1 → app2  ⟹  {name:srv1, logicalServers.name:ls1, logicalServers.applications.name:app2}
     *  → ls2 → app3  ⟹  {name:srv1, logicalServers.name:ls2, logicalServers.applications.name:app3}
     */
    protected function expandRow(Model $item, array $fields): array
    {
        // Séparer champs racine et champs de relation
        $rootFields     = [];
        $relationFields = []; // ['logicalServers' => ['name', 'applications.name']]

        foreach ($fields as $field) {
            $parts = explode('.', $field, 2);
            if (count($parts) === 1) {
                $rootFields[] = $field;
            } else {
                $relationFields[$parts[0]][] = $parts[1];
            }
        }

        // Valeurs des champs racine
        $rootRow = [];
        foreach ($rootFields as $f) {
            $rootRow[$f] = $item->getAttribute($f);
        }

        if (empty($relationFields)) {
            return [$rootRow];
        }

        // Produit cartésien des relations
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
                // Relation vide : garder les lignes avec null
                foreach ($result as &$row) {
                    foreach ($subFields as $sf) {
                        $row[$relation . '.' . $sf] = null;
                    }
                }
                unset($row);
                continue;
            }

            // Expansion : lignes existantes × objets liés
            $newResult = [];
            foreach ($result as $row) {
                foreach ($related as $relatedItem) {
                    // Récursion pour les sous-champs (ex: "applications.name")
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
    // Graphe — supporte traverse imbriqué (a.b, a.b.c)
    // ─────────────────────────────────────────────────────────────

    protected function buildGraph(
        Collection $items,
        string     $modelName,
        array      $traverse,
        int        $maxDepth
    ): GraphResult {
        $this->visitedNodes = [];
        $this->nodes        = [];
        $this->edges        = [];

        foreach ($items as $item) {
            $this->traverseNode($item, $modelName, $traverse, $maxDepth, null);
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
        int     $remainingDepth,
        ?string $parentNodeId
    ): void {
        $nodeId = $modelName . '_' . $item->getKey();

        if ($parentNodeId !== null && $parentNodeId !== $nodeId) {
            $edgeKey               = $parentNodeId . '||' . $nodeId;
            $this->edges[$edgeKey] = ['from' => $parentNodeId, 'to' => $nodeId];
        }

        if (isset($this->visitedNodes[$nodeId])) {
            return;
        }
        $this->visitedNodes[$nodeId] = true;
        $this->nodes[$nodeId]        = $this->buildNode($item, $nodeId, $modelName);

        if ($remainingDepth <= 0 || empty($traverse)) {
            return;
        }

        foreach ($traverse as $traversePath) {
            // "logicalServers.applications" → relation=logicalServers, reste=[applications]
            $parts    = explode('.', $traversePath, 2);
            $relation = $parts[0];
            $subPath  = $parts[1] ?? null;

            if (! method_exists($item, $relation)) {
                continue;
            }

            try {
                $related = $item->$relation;
                if (! $related) continue;

                $related = $related instanceof Model ? collect([$related]) : $related;
                if ($related->isEmpty()) continue;

                $relatedModelName = class_basename(get_class($related->first()));

                // Si chemin imbriqué, continuer sur le sous-chemin uniquement
                // Sinon, réutiliser tout le traverse pour la profondeur suivante
                $subTraverse = $subPath ? [$subPath] : $traverse;

                foreach ($related as $relatedItem) {
                    $this->traverseNode(
                        $relatedItem,
                        $relatedModelName,
                        $subTraverse,
                        $remainingDepth - 1,
                        $nodeId,
                    );
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