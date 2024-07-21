<?php

namespace App\Http\Controllers\Admin;

use App\DomaineAd;
use App\ForestAd;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDomaineAdRequest;
use App\Http\Requests\StoreDomaineAdRequest;
use App\Http\Requests\UpdateDomaineAdRequest;
use App\LogicalServer;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class DomaineAdController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('domaine_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAds = DomaineAd::all()->sortBy('name');

        return view(
            'admin.domaineAds.index',
            compact('domaineAds')
        );
    }

    public function create()
    {
        abort_if(Gate::denies('domaine_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domainesForestAds = ForestAd::all()->sortBy('name')->pluck('name', 'id');
        $logicalServers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');

        return view(
            'admin.domaineAds.create',
            compact('domainesForestAds', 'logicalServers')
        );
    }

    public function store(StoreDomaineAdRequest $request)
    {
        $domainAd = DomaineAd::create($request->all());
        $domainAd->domainesForestAds()->sync($request->input('domainesForestAds', []));

        LogicalServer::whereIn('id', $request->input('logicalServers', []))
            ->update(['domain_id' => $domainAd->id]);

        return redirect()->route('admin.domaine-ads.index');
    }

    public function edit(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domainesForestAds = ForestAd::all()->sortBy('name')->pluck('name', 'id');
        $logicalServers = LogicalServer::all()->sortBy('name')->pluck('name', 'id');
        $domaineAd->load('domainesForestAds');

        return view(
            'admin.domaineAds.edit',
            compact('domaineAd', 'domainesForestAds', 'logicalServers')
        );
    }

    public function update(UpdateDomaineAdRequest $request, DomaineAd $domaineAd)
    {
        $domaineAd->update($request->all());
        $domaineAd->domainesForestAds()->sync($request->input('domainesForestAds', []));

        LogicalServer::where('domain_id', $domaineAd->id)
            ->update(['domain_id' => null]);

        LogicalServer::whereIn('id', $request->input('logicalServers', []))
            ->update(['domain_id' => $domaineAd->id]);

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

        return redirect()->route('admin.domaine-ads.index');
    }

    public function massDestroy(MassDestroyDomaineAdRequest $request)
    {
        DomaineAd::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
