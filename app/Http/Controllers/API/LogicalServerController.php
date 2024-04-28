<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalServerRequest;
use App\Http\Requests\StoreLogicalServerRequest;
use App\Http\Requests\UpdateLogicalServerRequest;
use App\Http\Resources\Admin\LogicalServerResource;
use App\LogicalServer;
use Gate;
use Illuminate\Http\Response;

class LogicalServerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('logical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalservers = LogicalServer::all();

        return response()->json($logicalservers);
    }

    public function store(StoreLogicalServerRequest $request)
    {
        abort_if(Gate::denies('logical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer = LogicalServer::create($request->all());
        $logicalServer->servers()->sync($request->input('servers', []));
        $logicalServer->applications()->sync($request->input('applications', []));
        $logicalServer->databases()->sync($request->input('databases', []));

        return response()->json($logicalServer, 201);
    }

    public function show(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LogicalServerResource($logicalServer);
    }

    public function update(UpdateLogicalServerRequest $request, LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->update($request->all());
        $logicalServer->servers()->sync($request->input('servers', []));
        $logicalServer->applications()->sync($request->input('applications', []));
        $logicalServer->databases()->sync($request->input('databases', []));

        return response()->json();
    }

    public function destroy(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyLogicalServerRequest $request)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        LogicalServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
