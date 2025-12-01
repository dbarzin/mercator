<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalServerRequest;
use App\Http\Requests\StorePhysicalServerRequest;
use App\Http\Requests\UpdatePhysicalServerRequest;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\PhysicalServer;
use Symfony\Component\HttpFoundation\Response;

class PhysicalServerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServers = PhysicalServer::all();

        return response()->json($physicalServers);
    }

    public function store(StorePhysicalServerRequest $request)
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer = PhysicalServer::query()->create($request->all());
        if ($request->has('applications')) {
            $physicalServer->applications()->sync($request->input('applications', []));
        }

        if ($request->has('clusters')) {
            $physicalServer->clusters()->sync($request->input('clusters', []));
        }

        // Support for logical servers association via API
        if ($request->has('logicalServers')) {
            $logicalServerIds = $request->input('logicalServers', []);
            $physicalServer->logicalServers()->sync($logicalServerIds);
        }

        return response()->json($physicalServer, 201);
    }

    public function show(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($physicalServer);
    }

    public function update(UpdatePhysicalServerRequest $request, PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Update all fields except logicalServers (handled separately)
        $physicalServer->update($request->except('logicalServers'));

        if ($request->has('applications')) {
            $physicalServer->applications()->sync($request->input('applications', []));
        }

        if ($request->has('clusters')) {
            $physicalServer->clusters()->sync($request->input('clusters', []));
        }

        // Handle logical servers association via API
        if ($request->has('logicalServers')) {
            $logicalServerIds = $request->input('logicalServers', []);
            \Log::info("Physical server {$physicalServer->name} - syncing logical servers: ".json_encode($logicalServerIds));
            $physicalServer->logicalServers()->sync($logicalServerIds);
        }

        return response()->json();
    }

    public function destroy(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalServerRequest $request)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalServer::query()->whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
