<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePhysicalSwitchRequest;
use App\Http\Requests\UpdatePhysicalSwitchRequest;
use App\Http\Resources\Admin\PhysicalSwitchResource;
use App\PhysicalSwitch;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhysicalSwitchApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('physical_switch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalSwitchResource(PhysicalSwitch::with(['site', 'building', 'bay'])->get());
    }

    public function store(StorePhysicalSwitchRequest $request)
    {
        $physicalSwitch = PhysicalSwitch::create($request->all());

        return (new PhysicalSwitchResource($physicalSwitch))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalSwitchResource($physicalSwitch->load(['site', 'building', 'bay']));
    }

    public function update(UpdatePhysicalSwitchRequest $request, PhysicalSwitch $physicalSwitch)
    {
        $physicalSwitch->update($request->all());

        return (new PhysicalSwitchResource($physicalSwitch))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PhysicalSwitch $physicalSwitch)
    {
        abort_if(Gate::denies('physical_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitch->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
