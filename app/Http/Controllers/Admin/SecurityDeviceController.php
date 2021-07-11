<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\SecurityDevice;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityDeviceRequest;
use App\Http\Requests\StoreSecurityDeviceRequest;
use App\Http\Requests\UpdateSecurityDeviceRequest;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityDeviceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevices = SecurityDevice::all()->sortBy('name');

        return view('admin.securityDevices.index', compact('securityDevices'));
    }

    public function create()
    {
        abort_if(Gate::denies('security_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityDevices.create');
    }

    public function store(StoreSecurityDeviceRequest $request)
    {
        $securityDevice = SecurityDevice::create($request->all());

        return redirect()->route('admin.security-devices.index');
    }

    public function edit(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityDevices.edit', compact('securityDevice'));
    }

    public function update(UpdateSecurityDeviceRequest $request, SecurityDevice $securityDevice)
    {
        $securityDevice->update($request->all());

        return redirect()->route('admin.security-devices.index');
    }

    public function show(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.securityDevices.show', compact('securityDevice'));
    }

    public function destroy(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevice->delete();

        return back();
    }

    public function massDestroy(MassDestroySecurityDeviceRequest $request)
    {
        SecurityDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
