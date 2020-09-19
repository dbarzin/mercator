<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ExternalConnectedEntity;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExternalConnectedEntityRequest;
use App\Http\Requests\UpdateExternalConnectedEntityRequest;
use App\Http\Resources\Admin\ExternalConnectedEntityResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExternalConnectedEntityApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('external_connected_entity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExternalConnectedEntityResource(ExternalConnectedEntity::with(['connected_networks'])->get());
    }

    public function store(StoreExternalConnectedEntityRequest $request)
    {
        $externalConnectedEntity = ExternalConnectedEntity::create($request->all());
        $externalConnectedEntity->connected_networks()->sync($request->input('connected_networks', []));

        return (new ExternalConnectedEntityResource($externalConnectedEntity))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExternalConnectedEntityResource($externalConnectedEntity->load(['connected_networks']));
    }

    public function update(UpdateExternalConnectedEntityRequest $request, ExternalConnectedEntity $externalConnectedEntity)
    {
        $externalConnectedEntity->update($request->all());
        $externalConnectedEntity->connected_networks()->sync($request->input('connected_networks', []));

        return (new ExternalConnectedEntityResource($externalConnectedEntity))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalConnectedEntity->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
