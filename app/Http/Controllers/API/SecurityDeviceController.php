<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityDeviceRequest;
use App\Http\Requests\StoreSecurityDeviceRequest;
use App\Http\Requests\UpdateSecurityDeviceRequest;
use App\Models\SecurityDevice;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class SecurityDeviceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securitydevices = SecurityDevice::all();

        return response()->json($securitydevices);
    }

    public function store(StoreSecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('security_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securitydevice = SecurityDevice::create($request->all());

        return response()->json($securitydevice, 201);
    }

    public function show(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($securityDevice);
    }

    public function update(UpdateSecurityDeviceRequest $request, SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevice->update($request->all());
        // syncs
        // $securityDevice->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevice->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroySecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        SecurityDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
