<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEntityRequest;
use App\Http\Requests\StoreEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Models\Database;
use App\Models\Document;
use App\Models\Entity;
use App\Models\MApplication;
use App\Models\Process;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::with('processes', 'applications', 'databases')
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
        $icons = Entity::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view(
            'admin.entities.create',
            compact('processes', 'entityTypes', 'applications', 'databases', 'entities', 'icons')
        );
    }

    public function store(StoreEntityRequest $request)
    {
        $entity = Entity::create($request->all());

        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document;
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $entity->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $entity->icon_id = intval($request->iconSelect);
        } else {
            $entity->icon_id = null;
        }
        $entity->save();

        // Save relations
        $entity->processes()->sync($request->input('processes', []));

        // update applications table
        MApplication::whereIn('id', $request->input('respApplications', []))
            ->update(['entity_resp_id' => $entity->id]);

        // update databases table
        Database::whereIn('id', $request->input('databases', []))
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
        $entity->load('processes', 'applications', 'databases');
        $entities = Entity::orderBy('name')->pluck('name', 'id');
        $icons = Entity::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view(
            'admin.entities.edit',
            compact('entity', 'entityTypes', 'processes', 'applications', 'databases', 'entities', 'icons')
        );
    }

    public function update(UpdateEntityRequest $request, Entity $entity)
    {
        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document;
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $entity->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $entity->icon_id = intval($request->iconSelect);
        } else {
            $entity->icon_id = null;
        }

        // set is_external
        $request['is_external'] = $request->has('is_external');

        // Update fields
        $entity->update($request->all());

        // Save relations
        $entity->processes()->sync($request->input('processes', []));

        // update applications table
        MApplication::where('entity_resp_id', $entity->id)
            ->update(['entity_resp_id' => null]);

        MApplication::whereIn('id', $request->input('respApplications', []))
            ->update(['entity_resp_id' => $entity->id]);

        // update databases table
        Database::where('entity_resp_id', $entity->id)
            ->update(['entity_resp_id' => null]);

        Database::whereIn('id', $request->input('databases', []))
            ->update(['entity_resp_id' => $entity->id]);

        return redirect()->route('admin.entities.index');
    }

    public function show(Entity $entity)
    {
        abort_if(Gate::denies('entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entity->load('databases', 'applications', 'sourceRelations', 'destinationRelations', 'respApplications', 'processes');

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
