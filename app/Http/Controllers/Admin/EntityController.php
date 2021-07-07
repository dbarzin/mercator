<?php

namespace App\Http\Controllers\Admin;

use App\Entity;
use App\Process;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEntityRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::orderBy('name')->get();

        return view('admin.entities.index', compact('entities'));
    }

    public function create()
    {
        abort_if(Gate::denies('entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::orderBy('identifiant')->pluck('identifiant', 'id');

        return view('admin.entities.create',compact('processes'));
    }

    public function store(StoreEntityRequest $request)
    {
        $entity = Entity::create($request->all());

        $entity->entitiesProcesses()->sync($request->input('processes', []));

        return redirect()->route('admin.entities.index');
    }

    public function edit(Entity $entity)
    {
        abort_if(Gate::denies('entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::orderBy('identifiant')->pluck('identifiant', 'id');

        $entity->load('entitiesProcesses');

        return view('admin.entities.edit', compact('entity','processes'));
    }

    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        $entity->update($request->all());

        $entity->entitiesProcesses()->sync($request->input('processes', []));

        return redirect()->route('admin.entities.index');
    }

    public function show(Entity $entity)
    {
        abort_if(Gate::denies('entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity->load('entityRespDatabases', 'entityRespMApplications', 'sourceRelations', 'destinationRelations', 'entitiesMApplications', 'entitiesProcesses');

        return view('admin.entities.show', compact('entity'));
    }

    public function destroy(Entity $entity)
    {
        abort_if(Gate::denies('entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity->delete();

        return back();
    }

    public function massDestroy(MassDestroyEntityRequest $request)
    {
        Entity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
