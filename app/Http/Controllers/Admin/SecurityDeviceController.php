<?php

namespace App\Http\Controllers\Admin;

// Models
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityDeviceRequest;
// Framework
use App\Http\Requests\StoreSecurityDeviceRequest;
use App\Http\Requests\UpdateSecurityDeviceRequest;
use App\Models\PhysicalSecurityDevice;
use App\Models\SecurityDevice;
use Gate;
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

        $physicalSecurityDevices = PhysicalSecurityDevice::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.securityDevices.create', compact('physicalSecurityDevices'));
    }

    public function store(StoreSecurityDeviceRequest $request)
    {
        $securityDevice = SecurityDevice::create($request->all());

        // Relations
        $securityDevice->physicalSecurityDevices()->sync($request->input('physicalSecurityDevices', []));

        return redirect()->route('admin.security-devices.index');
    }

    public function edit(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevices = PhysicalSecurityDevice::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.securityDevices.edit', compact('securityDevice', 'physicalSecurityDevices'));
    }

    public function update(UpdateSecurityDeviceRequest $request, SecurityDevice $securityDevice)
    {
        $securityDevice->update($request->all());

        // Relations
        $securityDevice->physicalSecurityDevices()->sync($request->input('physical_security_devices', []));

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

        return redirect()->route('admin.security-devices.index');
    }

    public function massDestroy(MassDestroySecurityDeviceRequest $request)
    {
        SecurityDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
