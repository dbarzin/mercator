<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreInformationRequest;
use App\Http\Requests\UpdateInformationRequest;
use App\Http\Resources\Admin\InformationResource;
use App\Information;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InformationApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('information_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InformationResource(Information::with(['processes'])->get());
    }

    public function store(StoreInformationRequest $request)
    {
        $information = Information::create($request->all());
        $information->processes()->sync($request->input('processes', []));

        return (new InformationResource($information))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Information $information)
    {
        abort_if(Gate::denies('information_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InformationResource($information->load(['processes']));
    }

    public function update(UpdateInformationRequest $request, Information $information)
    {
        $information->update($request->all());
        $information->processes()->sync($request->input('processes', []));

        return (new InformationResource($information))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Information $information)
    {
        abort_if(Gate::denies('information_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $information->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
