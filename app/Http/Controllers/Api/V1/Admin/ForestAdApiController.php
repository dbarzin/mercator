<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ForestAd;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreForestAdRequest;
use App\Http\Requests\UpdateForestAdRequest;
use App\Http\Resources\Admin\ForestAdResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForestAdApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('forest_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ForestAdResource(ForestAd::with(['zone_admin', 'domaines'])->get());
    }

    public function store(StoreForestAdRequest $request)
    {
        $forestAd = ForestAd::create($request->all());
        $forestAd->domaines()->sync($request->input('domaines', []));

        return (new ForestAdResource($forestAd))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ForestAdResource($forestAd->load(['zone_admin', 'domaines']));
    }

    public function update(UpdateForestAdRequest $request, ForestAd $forestAd)
    {
        $forestAd->update($request->all());
        $forestAd->domaines()->sync($request->input('domaines', []));

        return (new ForestAdResource($forestAd))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ForestAd $forestAd)
    {
        abort_if(Gate::denies('forest_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forestAd->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
