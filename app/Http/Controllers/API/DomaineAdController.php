<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDomaineAdRequest;
use App\Http\Requests\StoreDomaineAdRequest;
use App\Http\Requests\UpdateDomaineAdRequest;
use App\Http\Resources\Admin\DomaineAdResource;
use App\Models\DomaineAd;
use Gate;
use Illuminate\Http\Response;

class DomaineAdController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('domaine_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd = DomaineAd::all();

        return response()->json($domaineAd);
    }

    public function store(StoreDomaineAdRequest $request)
    {
        abort_if(Gate::denies('domaine_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd = DomaineAd::create($request->all());
        if ($request['forestAds'] !== null) {
            $domaineAd->forestAds()->sync($request->input('forestAds', []));
        }
        if ($request['logicalServers'] !== null) {
            $domaineAd->logicalServers()->sync($request->input('logicalServers', []));
        }

        return response()->json($domaineAd, 201);
    }

    public function show(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DomaineAdResource($domaineAd);
    }

    public function update(UpdateDomaineAdRequest $request, DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd->update($request->all());
        if ($request['forestAds'] !== null) {
            $domaineAd->forestAds()->sync($request->input('forestAds', []));
        }
        if ($request['logicalServers'] !== null) {
            $domaineAd->logicalServers()->sync($request->input('logicalServers', []));
        }

        return response()->json();
    }

    public function destroy(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyDomaineAdRequest $request)
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DomaineAd::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
