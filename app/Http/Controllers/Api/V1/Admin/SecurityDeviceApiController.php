<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreSecurityDeviceRequest;
use App\Http\Requests\UpdateSecurityDeviceRequest;
use App\Http\Resources\Admin\SecurityDeviceResource;
use App\SecurityDevice;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityDeviceApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SecurityDeviceResource(SecurityDevice::all());
    }

    public function store(StoreSecurityDeviceRequest $request)
    {
        $securityDevice = SecurityDevice::create($request->all());

        return (new SecurityDeviceResource($securityDevice))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SecurityDeviceResource($securityDevice);
    }

    public function update(UpdateSecurityDeviceRequest $request, SecurityDevice $securityDevice)
    {
        $securityDevice->update($request->all());

        return (new SecurityDeviceResource($securityDevice))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevice->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
