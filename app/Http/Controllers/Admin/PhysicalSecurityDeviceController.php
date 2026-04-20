<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPhysicalSecurityDeviceRequest;
use App\Http\Requests\StorePhysicalSecurityDeviceRequest;
use App\Http\Requests\UpdatePhysicalSecurityDeviceRequest;
use App\Models\Bay;
use App\Models\Building;
use App\Models\PhysicalSecurityDevice;
use App\Models\SecurityDevice;
use App\Models\Site;
use App\Services\IconUploadService;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class PhysicalSecurityDeviceController extends Controller
{
    public function __construct(private readonly IconUploadService $iconUploadService) {}

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

        // Lists
        $type_list = PhysicalSecurityDevice::select('type')->where('type', '<>', null)
            ->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Select icons
        $icons = PhysicalSecurityDevice::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view(
            'admin.physicalSecurityDevices.create',
            compact('securityDevices',
                'sites',
                'buildings',
                'bays',
                'type_list',
                'attributes_list',
                'icons')
        );
    }

    public function store(StorePhysicalSecurityDeviceRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Create PhysicalSecurityDevice
        $physicalSecurityDevice = new PhysicalSecurityDevice($request->all());

        // Save icon
        $this->iconUploadService->handle($request, $physicalSecurityDevice);

        // Save PhysicalSecurityDevice
        $physicalSecurityDevice->save();

        // Relations
        $physicalSecurityDevice->securityDevices()->sync($request->input('security_devices', []));

        return redirect()->route('admin.physical-security-devices.index');
    }

    public function edit(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $securityDevices = SecurityDevice::all()->sortBy('name')->pluck('name', 'id');

        // Lists
        $type_list = PhysicalSecurityDevice::select('type')->where('type', '<>', null)
            ->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Select icons
        $icons = PhysicalSecurityDevice::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        $physicalSecurityDevice->load('site', 'building', 'bay');

        return view(
            'admin.physicalSecurityDevices.edit',
            compact('securityDevices',
                'sites',
                'buildings',
                'bays',
                'physicalSecurityDevice',
                'type_list',
                'attributes_list',
                'icons')
        );
    }

    public function update(UpdatePhysicalSecurityDeviceRequest $request, PhysicalSecurityDevice $physicalSecurityDevice)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Save icon
        $this->iconUploadService->handle($request, $physicalSecurityDevice);

        // Update PhysicalSecurityDevice
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

    private function getAttributes()
    {
        $attributes_list = PhysicalSecurityDevice::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);

        return array_unique($res);
    }
}
