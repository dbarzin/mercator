<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

// Models
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalSecurityDeviceRequest;
use App\Http\Requests\StorePhysicalSecurityDeviceRequest;
use App\Http\Requests\UpdatePhysicalSecurityDeviceRequest;
// Framework
use App\Models\Bay;
use App\Models\Building;
use App\Models\PhysicalSecurityDevice;
use App\Models\SecurityDevice;
use App\Models\Site;
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

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');

        $securityDevices = SecurityDevice::all()->sortBy('name')->pluck('name', 'id');

        $type_list = PhysicalSecurityDevice::select('type')->where('type', '<>', null)
            ->distinct()->orderBy('type')->pluck('type');

        return view(
            'admin.physicalSecurityDevices.create',
            compact('securityDevices', 'sites', 'buildings', 'bays', 'type_list')
        );
    }

    public function store(StorePhysicalSecurityDeviceRequest $request)
    {
        $physicalSecurityDevices = PhysicalSecurityDevice::create($request->all());

        // Relations
        $physicalSecurityDevices->securityDevices()->sync($request->input('security_devices', []));

        return redirect()->route('admin.physical-security-devices.index');
    }

    public function edit(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $securityDevices = SecurityDevice::all()->sortBy('name')->pluck('name', 'id');

        $type_list = PhysicalSecurityDevice::select('type')->where('type', '<>', null)
            ->distinct()->orderBy('type')->pluck('type');

        $physicalSecurityDevice->load('site', 'building', 'bay');

        return view(
            'admin.physicalSecurityDevices.edit',
            compact('securityDevices', 'sites', 'buildings', 'bays', 'physicalSecurityDevice', 'type_list')
        );
    }

    public function update(UpdatePhysicalSecurityDeviceRequest $request, PhysicalSecurityDevice $physicalSecurityDevice)
    {
        $physicalSecurityDevice->update($request->all());

        // Relations
        $physicalSecurityDevice->securityDevices()->sync($request->input('security_devices', []));

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

        return redirect()->route('admin.physical-security-devices.index');
    }

    public function massDestroy(MassDestroyPhysicalSecurityDeviceRequest $request)
    {
        PhysicalSecurityDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
