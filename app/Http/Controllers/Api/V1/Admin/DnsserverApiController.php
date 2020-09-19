<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Dnsserver;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDnsserverRequest;
use App\Http\Requests\UpdateDnsserverRequest;
use App\Http\Resources\Admin\DnsserverResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DnsserverApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('dnsserver_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DnsserverResource(Dnsserver::all());
    }

    public function store(StoreDnsserverRequest $request)
    {
        $dnsserver = Dnsserver::create($request->all());

        return (new DnsserverResource($dnsserver))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DnsserverResource($dnsserver);
    }

    public function update(UpdateDnsserverRequest $request, Dnsserver $dnsserver)
    {
        $dnsserver->update($request->all());

        return (new DnsserverResource($dnsserver))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dnsserver->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
