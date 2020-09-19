<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Bay;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreBayRequest;
use App\Http\Requests\UpdateBayRequest;
use App\Http\Resources\Admin\BayResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BayApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('bay_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BayResource(Bay::with(['room'])->get());
    }

    public function store(StoreBayRequest $request)
    {
        $bay = Bay::create($request->all());

        return (new BayResource($bay))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Bay $bay)
    {
        abort_if(Gate::denies('bay_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BayResource($bay->load(['room']));
    }

    public function update(UpdateBayRequest $request, Bay $bay)
    {
        $bay->update($request->all());

        return (new BayResource($bay))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Bay $bay)
    {
        abort_if(Gate::denies('bay_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bay->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
