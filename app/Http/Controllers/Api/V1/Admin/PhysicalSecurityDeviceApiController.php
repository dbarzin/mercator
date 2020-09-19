<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePhysicalSecurityDeviceRequest;
use App\Http\Requests\UpdatePhysicalSecurityDeviceRequest;
use App\Http\Resources\Admin\PhysicalSecurityDeviceResource;
use App\PhysicalSecurityDevice;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhysicalSecurityDeviceApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('physical_security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalSecurityDeviceResource(PhysicalSecurityDevice::with(['site', 'building', 'bay'])->get());
    }

    public function store(StorePhysicalSecurityDeviceRequest $request)
    {
        $physicalSecurityDevice = PhysicalSecurityDevice::create($request->all());

        return (new PhysicalSecurityDeviceResource($physicalSecurityDevice))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalSecurityDeviceResource($physicalSecurityDevice->load(['site', 'building', 'bay']));
    }

    public function update(UpdatePhysicalSecurityDeviceRequest $request, PhysicalSecurityDevice $physicalSecurityDevice)
    {
        $physicalSecurityDevice->update($request->all());

        return (new PhysicalSecurityDeviceResource($physicalSecurityDevice))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevice->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
