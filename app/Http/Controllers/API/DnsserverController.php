<?php

namespace App\Http\Controllers\API;

use App\Dnsserver;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDnsserverRequest;
use App\Http\Requests\StoreDnsserverRequest;
use App\Http\Requests\UpdateDnsserverRequest;
use App\Http\Resources\Admin\DnsserverResource;
use Gate;
use Illuminate\Http\Response;

class DnsserverController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dnsserver_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dnsservers = Dnsserver::all();

        return response()->json($dnsservers);
    }

    public function store(StoreDnsserverRequest $request)
    {
        abort_if(Gate::denies('dnsserver_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dnsserver = Dnsserver::create($request->all());
        // syncs
        // $dnsserver->roles()->sync($request->input('roles', []));

        return response()->json($dnsserver, 201);
    }

    public function show(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DnsserverResource($dnsserver);
    }

    public function update(UpdateDnsserverRequest $request, Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dnsserver->update($request->all());
        // syncs
        // $dnsserver->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dnsserver->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDnsserverRequest $request)
    {
        abort_if(Gate::denies('dnsserver_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Dnsserver::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
