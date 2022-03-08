<?php

namespace App\Http\Controllers\API;

use App\PhysicalSecurityDevice;

use App\Http\Requests\StorePhysicalSecurityDeviceRequest;
use App\Http\Requests\UpdatePhysicalSecurityDeviceRequest;
use App\Http\Requests\MassDestroyPhysicalSecurityDeviceRequest;
use App\Http\Resources\Admin\PhysicalSecurityDeviceResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

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
        // syncs
        // $physicalsecuritydevice->roles()->sync($request->input('roles', []));

        return response()->json($device, 201);
    }

    public function show(PhysicalSecurityDevice $physicalsecuritydevice)
    {
        abort_if(Gate::denies('physical_security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PhysicalSecurityDeviceResource($physicalsecuritydevice);
    }

    public function update(UpdatePhysicalSecurityDeviceRequest $request, PhysicalSecurityDevice $device)
    {     
        abort_if(Gate::denies('physical_security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $device->update($request->all());
        // syncs
        // $physicalsecuritydevice->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(PhysicalSecurityDevice $device)
    {
        abort_if(Gate::denies('physical_security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $device->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalSecurityDeviceRequest $request)
    {
        PhysicalSecurityDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

