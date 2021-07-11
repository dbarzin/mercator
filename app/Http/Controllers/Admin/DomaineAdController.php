<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\DomaineAd;
use App\ForestAd;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDomaineAdRequest;
use App\Http\Requests\StoreDomaineAdRequest;
use App\Http\Requests\UpdateDomaineAdRequest;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomaineAdController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('domaine_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAds = DomaineAd::all()->sortBy('name');

        return view('admin.domaineAds.index', compact('domaineAds'));
    }

    public function create()
    {
        abort_if(Gate::denies('domaine_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.domaineAds.create');
    }

    public function store(StoreDomaineAdRequest $request)
    {
        $domaineAd = DomaineAd::create($request->all());

        return redirect()->route('admin.domaine-ads.index');
    }

    public function edit(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domainesForestAds = ForestAd::all()->sortBy('name')->pluck('name', 'id');
        $domaineAd->load('domainesForestAds');

        return view('admin.domaineAds.edit', compact('domaineAd','domainesForestAds'));
    }

    public function update(UpdateDomaineAdRequest $request, DomaineAd $domaineAd)
    {
        $domaineAd->update($request->all());
        $domaineAd->domainesForestAds()->sync($request->input('domainesForestAds', []));

        return redirect()->route('admin.domaine-ads.index');
    }

    public function show(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd->load('domainesForestAds');

        return view('admin.domaineAds.show', compact('domaineAd'));
    }

    public function destroy(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd->delete();

        return back();
    }

    public function massDestroy(MassDestroyDomaineAdRequest $request)
    {
        DomaineAd::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
