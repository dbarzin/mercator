<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreLogicalServerRequest;
use App\Http\Requests\UpdateLogicalServerRequest;
use App\Http\Resources\Admin\LogicalServerResource;
use App\LogicalServer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogicalServerApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('logical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LogicalServerResource(LogicalServer::with(['servers'])->get());
    }

    public function store(StoreLogicalServerRequest $request)
    {
        $logicalServer = LogicalServer::create($request->all());
        $logicalServer->servers()->sync($request->input('servers', []));

        return (new LogicalServerResource($logicalServer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LogicalServerResource($logicalServer->load(['servers']));
    }

    public function update(UpdateLogicalServerRequest $request, LogicalServer $logicalServer)
    {
        $logicalServer->update($request->all());
        $logicalServer->servers()->sync($request->input('servers', []));

        return (new LogicalServerResource($logicalServer))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
