<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySiteRequest;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Http\Resources\Admin\SiteResource;
use App\Site;
use Gate;
use Illuminate\Http\Response;

class SiteController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('site_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all();

        return response()->json($sites);
    }

    public function store(StoreSiteRequest $request)
    {
        abort_if(Gate::denies('site_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $site = Site::create($request->all());
        // syncs
        // $site->roles()->sync($request->input('roles', []));

        return response()->json($site, 201);
    }

    public function show(Site $site)
    {
        abort_if(Gate::denies('site_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SiteResource($site);
    }

    public function update(UpdateSiteRequest $request, Site $site)
    {
        abort_if(Gate::denies('site_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $site->update($request->all());
        // syncs
        // $site->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(Site $site)
    {
        abort_if(Gate::denies('site_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $site->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroySiteRequest $request)
    {
        abort_if(Gate::denies('site_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Site::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
