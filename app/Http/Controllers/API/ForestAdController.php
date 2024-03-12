<?php

namespace App\Http\Controllers\API;

use App\ForestAd;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyForestAdRequest;
use App\Http\Requests\StoreForestAdRequest;
use App\Http\Requests\UpdateForestAdRequest;
use App\Http\Resources\Admin\ForestAdResource;
use Gate;
use Illuminate\Http\Response;

class ForestAdController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('forest_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestads = ForestAd::all();

        return response()->json($forestads);
    }

    public function store(StoreForestAdRequest $request)
    {
        abort_if(Gate::denies('forest_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestad = ForestAd::create($request->all());
        // syncs
        // $forestad->roles()->sync($request->input('roles', []));

        return response()->json($forestad, 201);
    }

    public function show(ForestAd $forestad)
    {
        abort_if(Gate::denies('forest_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ForestAdResource($forestad);
    }

    public function update(UpdateForestAdRequest $request, ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->update($request->all());
        // syncs
        // $forestAd->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyForestAdRequest $request)
    {
        abort_if(Gate::denies('forest_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ForestAd::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
