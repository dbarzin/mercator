<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalSecurityDeviceRequest;
use App\Http\Requests\StorePhysicalSecurityDeviceRequest;
use App\Http\Requests\UpdatePhysicalSecurityDeviceRequest;
use App\Http\Resources\Admin\PhysicalSecurityDeviceResource;
use App\PhysicalSecurityDevice;
use Gate;
use Illuminate\Http\Response;

class PhysicalSecurityDeviceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $devices = PhysicalSecurityDevice::all();

        return response()->json($devices);
    }

    public function store(StorePhysicalSecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('physical_security_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $device = PhysicalSecurityDevice::create($request->all());

        return response()->json($device, 201);
    }

    public function show(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalSecurityDeviceResource($physicalSecurityDevice);
    }

    public function update(UpdatePhysicalSecurityDeviceRequest $request, PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevice->update($request->all());
        // syncs
        // $physicalSecurityDevice->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevice->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalSecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('physical_security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalSecurityDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
