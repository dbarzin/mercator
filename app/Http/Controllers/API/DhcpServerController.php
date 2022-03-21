<?php

namespace App\Http\Controllers\API;

use App\DhcpServer;

use App\Http\Requests\StoreDhcpServerRequest;
use App\Http\Requests\UpdateDhcpServerRequest;
use App\Http\Requests\MassDestroyDhcpServerRequest;
use App\Http\Resources\Admin\DhcpServerResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

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

    public function show(DhcpServer $dhcpserver)
    {
        abort_if(Gate::denies('dhcp_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DhcpServerResource($dhcpserver);
    }

    public function update(UpdateDhcpServerRequest $request, DhcpServer $dhcpserver)
    {     
        abort_if(Gate::denies('dhcp_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpserver->update($request->all());
        // syncs
        // $dhcpserver->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(DhcpServer $dhcpserver)
    {
        abort_if(Gate::denies('dhcp_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpserver->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDhcpServerRequest $request)
    {
        DhcpServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

