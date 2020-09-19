<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\DomaineAd;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDomaineAdRequest;
use App\Http\Requests\UpdateDomaineAdRequest;
use App\Http\Resources\Admin\DomaineAdResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomaineAdApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('domaine_ad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DomaineAdResource(DomaineAd::all());
    }

    public function store(StoreDomaineAdRequest $request)
    {
        $domaineAd = DomaineAd::create($request->all());

        return (new DomaineAdResource($domaineAd))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DomaineAdResource($domaineAd);
    }

    public function update(UpdateDomaineAdRequest $request, DomaineAd $domaineAd)
    {
        $domaineAd->update($request->all());

        return (new DomaineAdResource($domaineAd))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DomaineAd $domaineAd)
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domaineAd->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
