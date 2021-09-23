<?php

namespace App\Http\Controllers\Admin;

use App\Bay;
use App\Building;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalSecurityDeviceRequest;
use App\Http\Requests\StorePhysicalSecurityDeviceRequest;
use App\Http\Requests\UpdatePhysicalSecurityDeviceRequest;
use App\PhysicalSecurityDevice;
use App\Site;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class PhysicalSecurityDeviceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('physical_security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevices = PhysicalSecurityDevice::all();

        return view('admin.physicalSecurityDevices.index', compact('physicalSecurityDevices'));
    }

    public function create()
    {
        abort_if(Gate::denies('physical_security_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.physicalSecurityDevices.create', compact('sites', 'buildings', 'bays'));
    }

    public function store(StorePhysicalSecurityDeviceRequest $request)
    {
        $physicalSecurityDevice = PhysicalSecurityDevice::create($request->all());

        return redirect()->route('admin.physical-security-devices.index');
    }

    public function edit(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $physicalSecurityDevice->load('site', 'building', 'bay');

        return view('admin.physicalSecurityDevices.edit', compact('sites', 'buildings', 'bays', 'physicalSecurityDevice'));
    }

    public function update(UpdatePhysicalSecurityDeviceRequest $request, PhysicalSecurityDevice $physicalSecurityDevice)
    {
        $physicalSecurityDevice->update($request->all());

        return redirect()->route('admin.physical-security-devices.index');
    }

    public function show(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevice->load('site', 'building', 'bay');

        return view('admin.physicalSecurityDevices.show', compact('physicalSecurityDevice'));
    }

    public function destroy(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevice->delete();

        return back();
    }

    public function massDestroy(MassDestroyPhysicalSecurityDeviceRequest $request)
    {
        PhysicalSecurityDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
