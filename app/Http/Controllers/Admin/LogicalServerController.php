<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogicalServerRequest;
use App\Http\Requests\StoreLogicalServerRequest;
use App\Http\Requests\UpdateLogicalServerRequest;
use App\LogicalServer;
use App\MApplication;
use App\PhysicalServer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class LogicalServerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('logical_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServers = LogicalServer::all()->sortBy('name');

        return view('admin.logicalServers.index', compact('logicalServers'));
    }

    public function create()
    {
        abort_if(Gate::denies('logical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $servers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');

        $operating_system_list = LogicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $environment_list = LogicalServer::select('environment')->where('environment', '<>', null)->distinct()->orderBy('environment')->pluck('environment');

        return view(
            'admin.logicalServers.create',
            compact('servers', 'applications', 'environment_list', 'operating_system_list')
        );
    }

    public function store(StoreLogicalServerRequest $request)
    {
        $logicalServer = LogicalServer::create($request->all());
        $logicalServer->servers()->sync($request->input('servers', []));
        $logicalServer->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.logical-servers.index');
    }

    public function edit(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $servers = PhysicalServer::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');

        $operating_system_list = LogicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $environment_list = LogicalServer::select('environment')->where('environment', '<>', null)->distinct()->orderBy('environment')->pluck('environment');

        $logicalServer->load('servers', 'applications');

        return view(
            'admin.logicalServers.edit',
            compact('servers', 'applications', 'operating_system_list', 'environment_list', 'logicalServer')
        );
    }

    public function update(UpdateLogicalServerRequest $request, LogicalServer $logicalServer)
    {
        $logicalServer->update($request->all());
        $logicalServer->servers()->sync($request->input('servers', []));
        $logicalServer->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.logical-servers.index');
    }

    public function show(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->load('servers', 'applications');

        return view('admin.logicalServers.show', compact('logicalServer'));
    }

    public function destroy(LogicalServer $logicalServer)
    {
        abort_if(Gate::denies('logical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logicalServer->delete();

        return redirect()->route('admin.logical-servers.index');
    }

    public function massDestroy(MassDestroyLogicalServerRequest $request)
    {
        LogicalServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
