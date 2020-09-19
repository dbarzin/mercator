<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreWorkstationRequest;
use App\Http\Requests\UpdateWorkstationRequest;
use App\Http\Resources\Admin\WorkstationResource;
use App\Workstation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkstationApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('workstation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorkstationResource(Workstation::with(['site', 'building'])->get());
    }

    public function store(StoreWorkstationRequest $request)
    {
        $workstation = Workstation::create($request->all());

        return (new WorkstationResource($workstation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WorkstationResource($workstation->load(['site', 'building']));
    }

    public function update(UpdateWorkstationRequest $request, Workstation $workstation)
    {
        $workstation->update($request->all());

        return (new WorkstationResource($workstation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Workstation $workstation)
    {
        abort_if(Gate::denies('workstation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $workstation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
