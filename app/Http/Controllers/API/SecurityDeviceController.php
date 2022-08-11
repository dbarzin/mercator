<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityDeviceRequest;
use App\Http\Requests\StoreSecurityDeviceRequest;
use App\Http\Requests\UpdateSecurityDeviceRequest;
use App\Http\Resources\Admin\SecurityDeviceResource;
use App\SecurityDevice;
use Gate;
use Illuminate\Http\Response;

class SecurityDeviceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('securitydevice_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securitydevices = SecurityDevice::all();

        return response()->json($securitydevices);
    }

    public function store(StoreSecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('securitydevice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securitydevice = SecurityDevice::create($request->all());
        // syncs
        // $securitydevice->roles()->sync($request->input('roles', []));

        return response()->json($securitydevice, 201);
    }

    public function show(SecurityDevice $securitydevice)
    {
        abort_if(Gate::denies('securitydevice_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SecurityDeviceResource($securitydevice);
    }

    public function update(UpdateSecurityDeviceRequest $request, SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('securitydevice_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevice->update($request->all());
        // syncs
        // $securityDevice->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('securitydevice_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevice->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroySecurityDeviceRequest $request)
    {
        SecurityDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
