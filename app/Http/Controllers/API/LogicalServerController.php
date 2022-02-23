<?php

namespace App\Http\Controllers\API;

use App\LogicalServer;

use App\Http\Requests\StoreLogicalServerRequest;
use App\Http\Requests\UpdateLogicalServerRequest;
use App\Http\Requests\MassDestroyLogicalServerRequest;
use App\Http\Resources\Admin\LogicalServerResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

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

        $logicalserver = LogicalServer::create($request->all());
        $logicalServer->servers()->sync($request->input('servers', []));
        $logicalServer->applications()->sync($request->input('applications', []));

        return response()->json($logicalserver, 201);
    }

    public function show(LogicalServer $logicalserver)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LogicalServerResource($logicalserver);
    }

    public function update(UpdateLogicalServerRequest $request, LogicalServer $logicalserver)
    {     
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalserver->update($request->all());
        $logicalServer->servers()->sync($request->input('servers', []));
        $logicalServer->applications()->sync($request->input('applications', []));

        return response()->json();
    }

    public function destroy(LogicalServer $logicalserver)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalserver->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyLogicalServerRequest $request)
    {
        LogicalServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

