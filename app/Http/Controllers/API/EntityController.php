<?php

namespace App\Http\Controllers\API;

use App\Entity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEntityRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Http\Resources\Admin\EntityResource;
use Gate;
use Illuminate\Http\Response;

class EntityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::all();

        return response()->json($entities);
    }

    public function store(StoreEntityRequest $request)
    {
        abort_if(Gate::denies('entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity = Entity::create($request->all());
        $entity->entitiesProcesses()->sync($request->input('processes', []));

        return response()->json($entity, 201);
    }

    public function show(Entity $entity)
    {
        abort_if(Gate::denies('entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EntityResource($entity);
    }

    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        abort_if(Gate::denies('entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity->update($request->all());
        $entity->entitiesProcesses()->sync($request->input('processes', []));

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

        Entity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
