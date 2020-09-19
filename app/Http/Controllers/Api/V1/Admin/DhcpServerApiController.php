<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DhcpServer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDhcpServerRequest;
use App\Http\Requests\UpdateDhcpServerRequest;
use App\Http\Resources\Admin\DhcpServerResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DhcpServerApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('dhcp_server_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DhcpServerResource(DhcpServer::all());
    }

    public function store(StoreDhcpServerRequest $request)
    {
        $dhcpServer = DhcpServer::create($request->all());

        return (new DhcpServerResource($dhcpServer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DhcpServerResource($dhcpServer);
    }

    public function update(UpdateDhcpServerRequest $request, DhcpServer $dhcpServer)
    {
        $dhcpServer->update($request->all());

        return (new DhcpServerResource($dhcpServer))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DhcpServer $dhcpServer)
    {
        abort_if(Gate::denies('dhcp_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dhcpServer->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
