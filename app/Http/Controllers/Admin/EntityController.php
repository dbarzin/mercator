<?php

namespace App\Http\Controllers\Admin;

use App\Database;
use App\Entity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEntityRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\MApplication;
use App\Process;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::with('entitiesProcesses', 'applications', 'databases')
            ->orderBy('name')->get();

        return view('admin.entities.index', compact('entities'));
    }

    public function create()
    {
        abort_if(Gate::denies('entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $processes = Process::orderBy('name')->pluck('name', 'id');
        $applications = MApplication::orderBy('name')->pluck('name', 'id');
        $databases = Database::orderBy('name')->pluck('name', 'id');
        $entityTypes = Entity::select('entity_type')
            ->where('entity_type', '<>', null)->distinct()
            ->orderBy('entity_type')->pluck('entity_type');
        $entities = Entity::orderBy('name')->pluck('name', 'id');

        return view('admin.entities.create', compact('processes', 'entityTypes', 'applications', 'databases', 'entities'));
    }

    public function store(StoreEntityRequest $request)
    {
        $entity = Entity::create($request->all());

        $entity->entitiesProcesses()->sync($request->input('processes', []));

        // update applications table
        DB::table('m_applications')
            ->where('entity_resp_id', $entity->id)
            ->update(['entity_resp_id' => null]);

        DB::table('m_applications')
            ->whereIn('id', $request->input('applications', []))
            ->update(['entity_resp_id' => $entity->id]);

        // update databases table
        DB::table('databases')
            ->where('entity_resp_id', $entity->id)
            ->update(['entity_resp_id' => null]);

        DB::table('databases')
            ->whereIn('id', $request->input('databases', []))
            ->update(['entity_resp_id' => $entity->id]);

        return redirect()->route('admin.entities.index');
    }

    public function edit(Entity $entity)
    {
        abort_if(Gate::denies('entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $processes = Process::orderBy('name')->pluck('name', 'id');
        $applications = MApplication::orderBy('name')->pluck('name', 'id');
        $databases = Database::orderBy('name')->pluck('name', 'id');
        $entityTypes = Entity::select('entity_type')
            ->where('entity_type', '<>', null)->distinct()
            ->orderBy('entity_type')->pluck('entity_type');
        $entity->load('entitiesProcesses', 'applications', 'databases');
        $entities = Entity::orderBy('name')->pluck('name', 'id');

        return view('admin.entities.edit', compact('entity', 'entityTypes', 'processes', 'applications', 'databases', 'entities'));
    }

    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        $entity->is_external = $request->has('is_external');
        $entity->update($request->all());
        $entity->entitiesProcesses()->sync($request->input('processes', []));

        // update applications table
        DB::table('m_applications')
            ->where('entity_resp_id', $entity->id)
            ->update(['entity_resp_id' => null]);

        DB::table('m_applications')
            ->whereIn('id', $request->input('applications', []))
            ->update(['entity_resp_id' => $entity->id]);

        // update databases table
        DB::table('databases')
            ->where('entity_resp_id', $entity->id)
            ->update(['entity_resp_id' => null]);

        DB::table('databases')
            ->whereIn('id', $request->input('databases', []))
            ->update(['entity_resp_id' => $entity->id]);

        return redirect()->route('admin.entities.index');
    }

    public function show(Entity $entity)
    {
        abort_if(Gate::denies('entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity->load('databases', 'applications', 'sourceRelations', 'destinationRelations', 'entitiesMApplications', 'entitiesProcesses');

        return view('admin.entities.show', compact('entity'));
    }

    public function destroy(Entity $entity)
    {
        abort_if(Gate::denies('entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity->delete();

        return redirect()->route('admin.entities.index');
    }

    public function massDestroy(MassDestroyEntityRequest $request)
    {
        Entity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
