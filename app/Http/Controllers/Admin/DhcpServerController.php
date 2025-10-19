<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDhcpServerRequest;
use App\Http\Requests\StoreDhcpServerRequest;
use App\Http\Requests\UpdateDhcpServerRequest;
use App\Models\DhcpServer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class DhcpServerController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dhcp_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpServers = DhcpServer::all()->sortBy('name');

        return view('admin.dhcpServers.index', compact('dhcpServers'));
    }

    public function create()
    {
        abort_if(Gate::denies('dhcp_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dhcpServers.create');
    }

    public function store(StoreDhcpServerRequest $request)
    {
        DhcpServer::create($request->all());

        return redirect()->route('admin.dhcp-servers.index');
    }

    public function edit(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dhcpServers.edit', compact('dhcpServer'));
    }

    public function update(UpdateDhcpServerRequest $request, DhcpServer $dhcpServer)
    {
        $dhcpServer->update($request->all());

        return redirect()->route('admin.dhcp-servers.index');
    }

    public function show(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dhcpServers.show', compact('dhcpServer'));
    }

    public function destroy(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpServer->delete();

        return redirect()->route('admin.dhcp-servers.index');
    }

    public function massDestroy(MassDestroyDhcpServerRequest $request)
    {
        DhcpServer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
