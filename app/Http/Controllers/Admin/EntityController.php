<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Entity;
use App\Process;
use App\MApplication;
use App\Database;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEntityRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $applications = MApplication::orderBy('name')->pluck('name', 'id');
        $databases = Database::orderBy('name')->pluck('name', 'id');

        return view('admin.entities.create',compact('processes','applications','databases'));
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

        $processes = Process::orderBy('identifiant')->pluck('identifiant', 'id');
        $applications = MApplication::orderBy('name')->pluck('name', 'id');
        $databases = Database::orderBy('name')->pluck('name', 'id');

        $entity->load('entitiesProcesses','applications','databases');

        return view('admin.entities.edit', compact('entity','processes','applications','databases'));
    }

    public function update(UpdateEntityRequest $request, Entity $entity)
    {
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

        return back();
    }

    public function massDestroy(MassDestroyEntityRequest $request)
    {
        Entity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
