<?php

namespace App\Http\Controllers\Admin;

use App\Dnsserver;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDnsserverRequest;
use App\Http\Requests\StoreDnsserverRequest;
use App\Http\Requests\UpdateDnsserverRequest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DnsserverController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('dnsserver_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dnsservers = Dnsserver::all()->sortBy('name');

        return view('admin.dnsservers.index', compact('dnsservers'));
    }

    public function create()
    {
        abort_if(Gate::denies('dnsserver_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dnsservers.create');
    }

    public function store(StoreDnsserverRequest $request)
    {
        $dnsserver = Dnsserver::create($request->all());

        return redirect()->route('admin.dnsservers.index');
    }

    public function edit(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dnsservers.edit', compact('dnsserver'));
    }

    public function update(UpdateDnsserverRequest $request, Dnsserver $dnsserver)
    {
        $dnsserver->update($request->all());

        return redirect()->route('admin.dnsservers.index');
    }

    public function show(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dnsservers.show', compact('dnsserver'));
    }

    public function destroy(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dnsserver->delete();

        return back();
    }

    public function massDestroy(MassDestroyDnsserverRequest $request)
    {
        Dnsserver::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
