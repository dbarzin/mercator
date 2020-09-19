<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePhysicalServerRequest;
use App\Http\Requests\UpdatePhysicalServerRequest;
use App\Http\Resources\Admin\PhysicalServerResource;
use App\PhysicalServer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhysicalServerApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('physical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalServerResource(PhysicalServer::with(['site', 'building', 'bay'])->get());
    }

    public function store(StorePhysicalServerRequest $request)
    {
        $physicalServer = PhysicalServer::create($request->all());

        return (new PhysicalServerResource($physicalServer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalServerResource($physicalServer->load(['site', 'building', 'bay']));
    }

    public function update(UpdatePhysicalServerRequest $request, PhysicalServer $physicalServer)
    {
        $physicalServer->update($request->all());

        return (new PhysicalServerResource($physicalServer))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PhysicalServer $physicalServer)
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalServer->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
