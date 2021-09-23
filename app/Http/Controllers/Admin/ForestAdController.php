<?php

namespace App\Http\Controllers\Admin;

use App\DomaineAd;
use App\ForestAd;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyForestAdRequest;
use App\Http\Requests\StoreForestAdRequest;
use App\Http\Requests\UpdateForestAdRequest;
use App\ZoneAdmin;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ForestAdController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('forest_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAds = ForestAd::all()->sortBy('name');

        return view('admin.forestAds.index', compact('forestAds'));
    }

    public function create()
    {
        abort_if(Gate::denies('forest_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zone_admins = ZoneAdmin::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domaines = DomaineAd::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.forestAds.create', compact('zone_admins', 'domaines'));
    }

    public function store(StoreForestAdRequest $request)
    {
        $forestAd = ForestAd::create($request->all());
        $forestAd->domaines()->sync($request->input('domaines', []));

        return redirect()->route('admin.forest-ads.index');
    }

    public function edit(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zone_admins = ZoneAdmin::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $domaines = DomaineAd::all()->sortBy('name')->pluck('name', 'id');

        $forestAd->load('zone_admin', 'domaines');

        return view('admin.forestAds.edit', compact('zone_admins', 'domaines', 'forestAd'));
    }

    public function update(UpdateForestAdRequest $request, ForestAd $forestAd)
    {
        $forestAd->update($request->all());
        $forestAd->domaines()->sync($request->input('domaines', []));

        return redirect()->route('admin.forest-ads.index');
    }

    public function show(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->load('zone_admin', 'domaines');

        return view('admin.forestAds.show', compact('forestAd'));
    }

    public function destroy(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->delete();

        return back();
    }

    public function massDestroy(MassDestroyForestAdRequest $request)
    {
        ForestAd::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
