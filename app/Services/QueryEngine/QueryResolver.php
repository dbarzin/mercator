<?php

namespace App\Services\QueryEngine;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class QueryResolver
{
    // ── Opérateurs SQL autorisés (whitelist sécurité) ───────────
    protected const ALLOWED_OPERATORS = ['=', '!=', '<', '>', '<=', '>=', 'like', 'in', 'not in'];

    // ── État de la traversée (réinitialisé à chaque execute) ────
    protected array $visitedNodes = [];
    protected array $nodes        = [];
    protected array $edges        = [];

    // ─────────────────────────────────────────────────────────────
    // Point d'entrée public
    // ─────────────────────────────────────────────────────────────

    public function execute(array $dsl): GraphResult|ListResult
    {
        $modelClass = QueryEngineIntrospector::resolveModelClass($dsl['from']);
        $builder    = $modelClass::query();

        // Appliquer les filtres
        foreach ($dsl['filters'] ?? [] as $filter) {
            $this->applyFilter($builder, $filter);
        }

        // Eager-load les relations de premier niveau pour éviter N+1
        $traverse = $dsl['traverse'] ?? [];
        if (! empty($traverse)) {
            $builder->with($traverse);
        }

        $items = $builder->limit($dsl['limit'] ?? 100)->get();

        return match ($dsl['output'] ?? 'list') {
            'graph' => $this->buildGraph($items, $dsl['from'], $traverse, $dsl['depth'] ?? 2),
            default => $this->buildList($items, $dsl),
        };
    }

    // ─────────────────────────────────────────────────────────────
    // Construction du graphe
    // ─────────────────────────────────────────────────────────────

    protected function buildGraph(
        Collection $items,
        string     $modelName,
        array      $traverse,
        int        $maxDepth
    ): GraphResult {
        // Réinitialise l'état pour permettre les appels successifs
        $this->visitedNodes = [];
        $this->nodes        = [];
        $this->edges        = [];

        foreach ($items as $item) {
            $this->traverseNode($item, $modelName, $traverse, $maxDepth, parentNodeId: null);
        }

        return new GraphResult(
            nodes: collect(array_values($this->nodes)),
            edges: collect(array_values($this->edges))
                ->unique(fn ($e) => $e['from'] . '||' . $e['to'])
                ->values(),
        );
    }

    /**
     * Traite un nœud et descend récursivement dans ses relations.
     *
     * @param  Model       $item           L'objet Eloquent courant
     * @param  string      $modelName      Nom court du modèle (ex: "LogicalServer")
     * @param  array       $traverse       Relations à traverser
     * @param  int         $remainingDepth Niveaux restants avant d'arrêter la récursion
     * @param  string|null $parentNodeId   ID du nœud parent (null pour les racines)
     */
    protected function traverseNode(
        Model   $item,
        string  $modelName,
        array   $traverse,
        int     $remainingDepth,
        ?string $parentNodeId
    ): void {
        $nodeId = $modelName . '_' . $item->getKey();

        // ── Ajouter l'arête vers le parent ─────────────────────
        if ($parentNodeId !== null && $parentNodeId !== $nodeId) {
            $edgeKey = $parentNodeId . '||' . $nodeId;
            $this->edges[$edgeKey] = [
                'from' => $parentNodeId,
                'to'   => $nodeId,
            ];
        }

        // ── Déjà visité → ne pas retraiter (anti-cycle) ────────
        if (isset($this->visitedNodes[$nodeId])) {
            return;
        }
        $this->visitedNodes[$nodeId] = true;

        // ── Enregistrer le nœud ────────────────────────────────
        $this->nodes[$nodeId] = $this->buildNode($item, $nodeId, $modelName);

        // ── Arrêt de récursion ─────────────────────────────────
        if ($remainingDepth <= 0 || empty($traverse)) {
            return;
        }

        // ── Descendre dans chaque relation ─────────────────────
        foreach ($traverse as $relationName) {
            if (! method_exists($item, $relationName)) {
                continue;
            }

            try {
                $related = $item->$relationName;

                if (! $related) {
                    continue;
                }

                // Normalise en collection (HasOne / BelongsTo retournent un Model)
                $related = $related instanceof Model ? collect([$related]) : $related;

                if ($related->isEmpty()) {
                    continue;
                }

                // Nom du modèle lié (ex: "Application")
                $relatedModelName = class_basename(get_class($related->first()));

                // Eager-load pour le niveau suivant si on continue la récursion
                if ($remainingDepth > 1 && ! empty($traverse)) {
                    $related->load($traverse);
                }

                foreach ($related as $relatedItem) {
                    $this->traverseNode(
                        $relatedItem,
                        $relatedModelName,
                        $traverse,
                        $remainingDepth - 1,
                        $nodeId,
                    );
                }
            } catch (\Throwable) {
                // Relation absente ou incompatible → on passe
                continue;
            }
        }
    }

    /**
     * Construit le tableau de données d'un nœud.
     * Le `label` est utilisé par Graphviz, `data` pour le tooltip HTML.
     */
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

    /**
     * Génère l'URL de la fiche détail d'un objet dans Mercator.
     * Convention de nommage : LogicalServer → /logical-servers/{id}
     */
    protected function resolveUrl(string $modelName, int|string $id): string
    {
        $slug = \Illuminate\Support\Str::kebab($modelName) . 's'; // LogicalServer → logical-servers
        return "/{$slug}/{$id}";
    }

    // ─────────────────────────────────────────────────────────────
    // Construction de la liste
    // ─────────────────────────────────────────────────────────────

    protected function buildList(Collection $items, array $dsl): ListResult
    {
        $select   = $dsl['select'] ?? null;
        $traverse = $dsl['traverse'] ?? [];

        $rows = $items->map(function (Model $item) use ($select, $traverse) {
            // Colonnes sélectionnées (ou toutes si non précisé)
            $row = $select
                ? collect($item->toArray())->only($select)->toArray()
                : collect($item->toArray())
                    ->except(['created_at', 'updated_at', 'deleted_at'])
                    ->toArray();

            // Aplatir les relations en liste de noms
            foreach ($traverse as $relationName) {
                if (! method_exists($item, $relationName)) {
                    continue;
                }
                try {
                    $related = $item->$relationName;
                    if (! $related) {
                        $row[$relationName] = '';
                        continue;
                    }
                    $related = $related instanceof Model ? collect([$related]) : $related;
                    $row[$relationName] = $related
                        ->pluck('name')
                        ->filter()
                        ->join(', ');
                } catch (\Throwable) {
                    $row[$relationName] = '';
                }
            }

            return $row;
        });

        $columns = array_keys($rows->first() ?? []);

        return new ListResult(rows: $rows, columns: $columns);
    }

    // ─────────────────────────────────────────────────────────────
    // Filtres
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

        // ── Filtre sur une relation : "relation.field" ──────────
        if (str_contains($filter['field'], '.')) {
            [$relation, $field] = explode('.', $filter['field'], 2);

            $relation = preg_replace('/[^a-zA-Z0-9_]/', '', $relation);
            $field    = preg_replace('/[^a-zA-Z0-9_]/', '', $field);

            $builder->whereHas($relation, function (Builder $q) use ($field, $operator, $value) {
                $table = $q->getModel()->getTable();
                abort_if(
                    ! Schema::hasColumn($table, $field),
                    422,
                    "Colonne inconnue : [{$field}] dans [{$table}]"
                );

                match ($operator) {
                    'in'     => $q->whereIn($field, (array) $value),
                    'not in' => $q->whereNotIn($field, (array) $value),
                    default  => $q->where($field, $operator, $value),
                };
            });

            return;
        }

        // ── Filtre sur la table racine ───────────────────────────
        $field = preg_replace('/[^a-zA-Z0-9_]/', '', $filter['field']);
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
}