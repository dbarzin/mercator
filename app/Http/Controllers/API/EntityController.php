<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\MassDestroyEntityRequest;
use App\Http\Requests\MassStoreEntityRequest;
use App\Http\Requests\MassUpdateEntityRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\Entity;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends APIController
{
    protected string $modelClass = Entity::class;

    public function index(Request $request)
    {
        abort_if(Gate::denies('entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->indexResource($request);
    }

    public function store(StoreEntityRequest $request)
    {
        abort_if(Gate::denies('entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var Entity $entity */
        $entity = Entity::create($request->all());

        if ($request->has('processes') && $request->input('processes') !== null) {
            $entity->processes()->sync($request->input('processes', []));
        }

        return response()->json($entity, Response::HTTP_CREATED);
    }

    public function show(Entity $entity)
    {
        abort_if(Gate::denies('entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($entity);
    }

    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        abort_if(Gate::denies('entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity->update($request->all());

        if ($request->has('processes') && $request->input('processes') !== null) {
            $entity->processes()->sync($request->input('processes', []));
        }

        return response()->json();
    }

    public function destroy(Entity $entity)
    {
        abort_if(Gate::denies('entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyEntityRequest $request)
    {
        abort_if(Gate::denies('entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Entity::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreEntityRequest $request)
    {
        // L’authorize() du FormRequest gère déjà entity_create
        $data       = $request->validated();
        $createdIds = [];

        $model    = new Entity();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $item) {
            $processes = $item['processes'] ?? null;

            // Ne garde que les colonnes du modèle, sans les relations
            $attributes = collect($item)
                ->except(['processes'])
                ->only($fillable)
                ->toArray();

            /** @var Entity $entity */
            $entity = Entity::query()->create($attributes);

            if (array_key_exists('processes', $item) && $processes !== null) {
                $entity->processes()->sync($processes ?? []);
            }

            $createdIds[] = $entity->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateEntityRequest $request)
    {
        // L’authorize() du FormRequest gère déjà entity_edit
        $data     = $request->validated();
        $model    = new Entity();
        $fillable = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id        = $rawItem['id'];
            $processes = $rawItem['processes'] ?? null;

            /** @var Entity $entity */
            $entity = Entity::query()->findOrFail($id);

            // Ne garde que les colonnes du modèle, sans l'id ni les relations
            $attributes = collect($rawItem)
                ->except(['id', 'processes'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $entity->update($attributes);
            }

            if (array_key_exists('processes', $rawItem) && $processes !== null) {
                $entity->processes()->sync($processes ?? []);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
