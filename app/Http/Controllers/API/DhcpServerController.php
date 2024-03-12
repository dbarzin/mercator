<?php

namespace App\Http\Controllers\API;

use App\DhcpServer;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDhcpServerRequest;
use App\Http\Requests\StoreDhcpServerRequest;
use App\Http\Requests\UpdateDhcpServerRequest;
use App\Http\Resources\Admin\DhcpServerResource;
use Gate;
use Illuminate\Http\Response;

class DhcpServerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dhcp_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpservers = DhcpServer::all();

        return response()->json($dhcpservers);
    }

    public function store(StoreDhcpServerRequest $request)
    {
        abort_if(Gate::denies('dhcp_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpserver = DhcpServer::create($request->all());
        // syncs
        // $dhcpserver->roles()->sync($request->input('roles', []));

        return response()->json($dhcpserver, 201);
    }

    public function show(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DhcpServerResource($dhcpServer);
    }

    public function update(UpdateDhcpServerRequest $request, DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpServer->update($request->all());
        // syncs
        // $dhcpServer->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpServer->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDhcpServerRequest $request)
    {
        abort_if(Gate::denies('dhcp_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DhcpServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
