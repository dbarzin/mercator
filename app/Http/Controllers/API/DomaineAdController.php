<?php

namespace App\Http\Controllers\API;

use App\DomaineAd;

use App\Http\Requests\StoreDomaineAdRequest;
use App\Http\Requests\UpdateDomaineAdRequest;
use App\Http\Requests\MassDestroyDomaineAdRequest;
use App\Http\Resources\Admin\DomaineAdResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class DomaineAdController extends Controller
{
    public function index()
    {
    abort_if(Gate::denies('domaine_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $domaineads = DomaineAd::all();

    return response()->json($domaineads);
    }

    public function store(StoreDomaineAdRequest $request)
    {
        abort_if(Gate::denies('domaine_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domainead = DomaineAd::create($request->all());
        $domaineAd->domainesForestAds()->sync($request->input('domainesForestAds', []));

        return response()->json($domainead, 201);
    }

    public function show(DomaineAd $domainead)
    {
        abort_if(Gate::denies('domaine_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DomaineAdResource($domainead);
    }

    public function update(UpdateDomaineAdRequest $request, DomaineAd $domainead)
    {     
        abort_if(Gate::denies('domaine_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domainead->update($request->all());
        $domaineAd->domainesForestAds()->sync($request->input('domainesForestAds', []));

        return response()->json();
    }

    public function destroy(DomaineAd $domainead)
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domainead->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDomaineAdRequest $request)
    {
        DomaineAd::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

