<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

abstract class APIController extends Controller
{
    protected string $modelClass;

    protected function newQuery(): Builder
    {
        return $this->modelClass::query();
    }

    protected function newModelInstance(): Model
    {
        return new $this->modelClass();
    }

    /**
     * Auto-détecte les relations du modèle via Reflection
     */
    protected function getAllowedIncludes(): array
    {
        $model = $this->newModelInstance();
        $relations = [];

        try {
            $reflection = new ReflectionClass($model);

            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->class !== $model::class) {
                    continue;
                }

                if ($method->getNumberOfParameters() !== 0) {
                    continue;
                }

                $returnType = $method->getReturnType();
                if (!$returnType instanceof ReflectionNamedType) {
                    continue;
                }

                $returnTypeName = $returnType->getName();

                if (is_subclass_of($returnTypeName, Relation::class)) {
                    $relations[] = $method->getName();
                }
            }
        } catch (\Throwable $e) {
            \Log::warning("Failed to auto-detect relations for {$model}: " . $e->getMessage());
        }

        return $relations;
    }

    /**
     * Champs textuels pour filtres partiels (LIKE)
     */
    protected function getPartialFilterFields(): array
    {
        $model = $this->newModelInstance();
        $fillable = $model->getFillable();

        return array_filter($fillable, function ($field) {
            return str_contains($field, 'name')
                || str_contains($field, 'description')
                || str_contains($field, 'email');
        });
    }

    /**
     * Relations avec champs textuels pour recherche LIKE
     */
    protected function getRelationPartialFilters(): array
    {
        $filters = [];
        $relations = $this->getAllowedIncludes();
        $textFields = ['name', 'email', 'description'];

        foreach ($relations as $relation) {
            try {
                $related = $this->newModelInstance()->{$relation}()->getRelated();
                $fillable = $related->getFillable();
                foreach ($textFields as $field) {
                    if (in_array($field, $fillable, true)) {
                        $filters[] = AllowedFilter::partial("{$relation}.{$field}");
                    }
                }
            } catch (\Throwable $e) {
                // Skip relations that cannot be resolved
            }
        }

        return $filters;
    }

    protected function indexResource(Request $request)
    {
        $model = $this->newModelInstance();
        $fillable = $model->getFillable();

        $allowedFilters = [];

        foreach ($fillable as $field) {
            // 1. Filtre exact
            $allowedFilters[] = AllowedFilter::exact($field);

            // 2. Filtres partiels (LIKE) pour champs textuels
            if (in_array($field, $this->getPartialFilterFields())) {
                $allowedFilters[] = AllowedFilter::partial($field);
            }

            // 3. Filtre de négation (NOT)
            $allowedFilters[] = AllowedFilter::callback(
                $field . '_not',
                function (Builder $query, $value) use ($field) {
                    $query->where($field, '!=', $value);
                }
            );

            // 4. Filtres numériques
            // 4. Filtres numériques
            $allowedFilters[] = AllowedFilter::callback(
                $field . '_lt',
                function (Builder $query, $value) use ($field) {
                    $query->where($field, '<', $value);
                }
            );

            $allowedFilters[] = AllowedFilter::callback(
                $field . '_lte',
                function (Builder $query, $value) use ($field) {
                    $query->where($field, '<=', $value);
                }
            );

            $allowedFilters[] = AllowedFilter::callback(
                $field . '_gt',
                function (Builder $query, $value) use ($field) {
                    $query->where($field, '>', $value);
                }
            );

            $allowedFilters[] = AllowedFilter::callback(
                $field . '_gte',
                function (Builder $query, $value) use ($field) {
                    $query->where($field, '>=', $value);
                }
            );

            // 5. WHERE IN
            $allowedFilters[] = AllowedFilter::callback(
                $field . '_in',
                function (Builder $query, $value) use ($field) {
                    $values = is_array($value) ? $value : explode(',', $value);
                    $query->whereIn($field, $values);
                }
            );

            // 6. BETWEEN
            $allowedFilters[] = AllowedFilter::callback(
                $field . '_between',
                function (Builder $query, $value) use ($field) {
                    $values = is_array($value) ? $value : explode(',', $value);
                    if (count($values) === 2) {
                        $query->whereBetween($field, $values);
                    }
                }
            );

            // 7. IS NULL / IS NOT NULL
            $allowedFilters[] = AllowedFilter::callback(
                $field . '_null',
                function (Builder $query, $value) use ($field) {
                    if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                        $query->whereNull($field);
                    } else {
                        $query->whereNotNull($field);
                    }
                }
            );

            // 8. Filtres de dates
            if (str_contains($field, 'date') || str_contains($field, 'at')) {
                $allowedFilters[] = AllowedFilter::callback(
                    $field . '_after',
                    function (Builder $query, $value) use ($field) {
                        $query->where($field, '>=', $value);
                    }
                );

                $allowedFilters[] = AllowedFilter::callback(
                    $field . '_before',
                    function (Builder $query, $value) use ($field) {
                        $query->where($field, '<=', $value);
                    }
                );
            }
        }

        // 8. Soft Deletes
        if (method_exists($model, 'trashed')) {
            $allowedFilters[] = AllowedFilter::trashed();
        }

        // 9. Recherche globale (OR sur plusieurs champs)
        $searchableFields = $this->getPartialFilterFields();
        if (!empty($searchableFields)) {
            $allowedFilters[] = AllowedFilter::callback(
                'search',
                function (Builder $query, $value) use ($searchableFields) {
                    $query->where(function ($q) use ($value, $searchableFields) {
                        foreach ($searchableFields as $field) {
                            $q->orWhere($field, 'LIKE', "%{$value}%");
                        }
                    });
                }
            );
        }

        // 10. Filtres sur les relations (actors, contacts, etc.)
        $relationFilters = $this->getRelationPartialFilters();
        $allowedFilters = array_merge($allowedFilters, $relationFilters);

        // Tris autorisés
        $allowedSorts = ['id', 'created_at', 'updated_at'];
        foreach ($fillable as $field) {
            $allowedSorts[] = $field;
        }

        // Auto-détection des relations
        $allowedIncludes = $this->getAllowedIncludes();

        $queryBuilder = QueryBuilder::for($this->modelClass)
            ->allowedFilters($allowedFilters)
            ->allowedSorts($allowedSorts);

        if (!empty($allowedIncludes)) {
            $queryBuilder->allowedIncludes($allowedIncludes);
        }

        $items = $queryBuilder->get();

        return response()->json($items);
    }

    protected function storeResource(array $data): Model
    {
        return $this->modelClass::query()->create($data);
    }

    protected function updateResource(Model $model, array $data): void
    {
        $model->update($data);
    }

    protected function destroyResource(Model $model): void
    {
        $model->delete();
    }

    protected function massDestroyByIds(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $this->newQuery()->whereIn('id', $ids)->delete();
    }

    protected function massStoreItems(array $items): array
    {
        $createdIds = [];

        foreach ($items as $item) {
            $attributes = collect($item)
                ->only($this->newModelInstance()->getFillable())
                ->toArray();

            $created = $this->newQuery()->create($attributes);
            $createdIds[] = $created->getKey();
        }

        return $createdIds;
    }

    protected function massUpdateItems(array $items): void
    {
        $fillable = $this->newModelInstance()->getFillable();

        foreach ($items as $rawItem) {
            if (!isset($rawItem['id'])) {
                continue;
            }

            $instance = $this->newQuery()->findOrFail($rawItem['id']);

            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (!empty($attributes)) {
                $instance->update($attributes);
            }
        }
    }

    protected function asJsonResource(Model $model): JsonResource
    {
        return new JsonResource($model);
    }
}