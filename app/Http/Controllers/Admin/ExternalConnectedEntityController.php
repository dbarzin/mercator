<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExternalConnectedEntityRequest;
use App\Http\Requests\StoreExternalConnectedEntityRequest;
use App\Http\Requests\UpdateExternalConnectedEntityRequest;
use App\Models\Entity;
use App\Models\ExternalConnectedEntity;
use App\Models\Network;
use App\Models\Subnetwork;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ExternalConnectedEntityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('external_connected_entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalConnectedEntities = ExternalConnectedEntity::all()->sortBy('name');

        return view('admin.externalConnectedEntities.index', compact('externalConnectedEntities'));
    }

    public function create()
    {
        abort_if(Gate::denies('external_connected_entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networks = Network::all()->sortBy('name')->pluck('name', 'id');
        $subnetworks = Subnetwork::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');

        $type_list = ExternalConnectedEntity::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        // Clear documents from session
        session()->put('documents', []);

        return view(
            'admin.externalConnectedEntities.create',
            compact('networks', 'subnetworks', 'entities', 'type_list')
        );
    }

    public function store(StoreExternalConnectedEntityRequest $request)
    {
        $externalConnectedEntity = ExternalConnectedEntity::create($request->all());
        $externalConnectedEntity->subnetworks()->sync($request->input('subnetworks', []));

        // Documents
        $externalConnectedEntity->documents()->sync(session()->get('documents'));
        session()->forget('documents');

        return redirect()->route('admin.external-connected-entities.index');
    }

    public function edit(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networks = Network::all()->sortBy('name')->pluck('name', 'id');
        $subnetworks = Subnetwork::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');

        $type_list = ExternalConnectedEntity::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');

        // Get Documents
        $documents = [];
        foreach ($externalConnectedEntity->documents as $doc) {
            array_push($documents, $doc->id);
        }
        session()->put('documents', $documents);

        return view(
            'admin.externalConnectedEntities.edit',
            compact('externalConnectedEntity', 'networks', 'subnetworks', 'entities', 'type_list')
        );
    }

    public function update(UpdateExternalConnectedEntityRequest $request, ExternalConnectedEntity $externalConnectedEntity)
    {
        $externalConnectedEntity->update($request->all());
        $externalConnectedEntity->subnetworks()->sync($request->input('subnetworks', []));

        $externalConnectedEntity->documents()->sync(session()->get('documents'));
        session()->forget('documents');

        return redirect()->route('admin.external-connected-entities.index');
    }

    public function show(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.externalConnectedEntities.show', compact('externalConnectedEntity'));
    }

    public function destroy(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalConnectedEntity->delete();

        return redirect()->route('admin.external-connected-entities.index');
    }

    public function massDestroy(MassDestroyExternalConnectedEntityRequest $request)
    {
        ExternalConnectedEntity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
