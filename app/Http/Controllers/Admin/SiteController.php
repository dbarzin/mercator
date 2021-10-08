<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySiteRequest;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Site;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('site_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name');

        return view('admin.sites.index', compact('sites'));
    }

    public function create()
    {
        abort_if(Gate::denies('site_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sites.create');
    }

    public function store(StoreSiteRequest $request)
    {
        $site = Site::create($request->all());

        return redirect()->route('admin.sites.index');
    }

    public function edit(Site $site)
    {
        abort_if(Gate::denies('site_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sites.edit', compact('site'));
    }

    public function update(UpdateSiteRequest $request, Site $site)
    {
        $site->update($request->all());

        return redirect()->route('admin.sites.index');
    }

    public function show(Site $site)
    {
        abort_if(Gate::denies('site_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $site->load('siteBuildings', 'sitePhysicalServers', 'siteWorkstations', 'siteStorageDevices', 'sitePeripherals', 'sitePhones', 'sitePhysicalSwitches');

        return view('admin.sites.show', compact('site'));
    }

    public function destroy(Site $site)
    {
        abort_if(Gate::denies('site_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $site->delete();

        return redirect()->route('admin.sites.index');
    }

    public function massDestroy(MassDestroySiteRequest $request)
    {
        Site::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
