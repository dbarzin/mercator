<?php

namespace App\Http\Controllers\API;

use App\ExternalConnectedEntity;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExternalConnectedEntityRequest;
use App\Http\Requests\StoreExternalConnectedEntityRequest;
use App\Http\Requests\UpdateExternalConnectedEntityRequest;
use App\Http\Resources\Admin\ExternalConnectedEntityResource;
use Gate;
use Illuminate\Http\Response;

class ExternalConnectedEntityController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('external_connected_entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = ExternalConnectedEntity::all();

        return response()->json($entities);
    }

    public function store(StoreExternalConnectedEntityRequest $request)
    {
        abort_if(Gate::denies('external_connected_entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalconnectedentity = ExternalConnectedEntity::create($request->all());
        // syncs
        // $externalconnectedentity->roles()->sync($request->input('roles', []));

        return response()->json($externalconnectedentity, 201);
    }

    public function show(ExternalConnectedEntity $externalconnectedentity)
    {
        abort_if(Gate::denies('external_connected_entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExternalConnectedEntityResource($externalconnectedentity);
    }

    public function update(UpdateExternalConnectedEntityRequest $request, ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalConnectedEntity->update($request->all());
        // syncs
        // $externalConnectedEntity->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalConnectedEntity->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyExternalConnectedEntityRequest $request)
    {
        abort_if(Gate::denies('external_connected_entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ExternalConnectedEntity::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
