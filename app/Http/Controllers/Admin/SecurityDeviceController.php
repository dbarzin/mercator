<?php


namespace App\Http\Controllers\Admin;

// Models
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityDeviceRequest;
use App\Http\Requests\StoreSecurityDeviceRequest;
use App\Http\Requests\UpdateSecurityDeviceRequest;
use App\Models\Document;
use App\Models\MApplication;
use App\Models\PhysicalSecurityDevice;
use App\Models\SecurityDevice;
use Gate;
use Symfony\Component\HttpFoundation\Response;

// Framework

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
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');

        // List
        $type_list = SecurityDevice::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Select icons
        $icons = SecurityDevice::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view('admin.securityDevices.create',
            compact('physicalSecurityDevices',
                'applications',
                'type_list',
                'attributes_list',
                'icons'
            ));
    }

    public function store(StoreSecurityDeviceRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $securityDevice = SecurityDevice::create($request->all());

        // Relations
        $securityDevice->physicalSecurityDevices()->sync($request->input('physical_security_devices', []));
        $securityDevice->applications()->sync($request->input('applications', []));

        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $securityDevice->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $securityDevice->icon_id = intval($request->iconSelect);
        } else {
            $securityDevice->icon_id = null;
        }
        $securityDevice->save();

        return redirect()->route('admin.security-devices.index');
    }

    public function edit(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevices = PhysicalSecurityDevice::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');

        // List
        $type_list = SecurityDevice::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Select icons
        $icons = SecurityDevice::query()->select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        return view('admin.securityDevices.edit',
            compact('securityDevice',
            'physicalSecurityDevices',
            'applications',
            'type_list',
            'attributes_list',
            'icons'
            ));
    }

    public function update(UpdateSecurityDeviceRequest $request, SecurityDevice $securityDevice)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $securityDevice->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $securityDevice->icon_id = intval($request->iconSelect);
        } else {
            $securityDevice->icon_id = null;
        }

        $securityDevice->update($request->all());

        // Relations
        $securityDevice->physicalSecurityDevices()->sync($request->input('physical_security_devices', []));
        $securityDevice->applications()->sync($request->input('applications', []));

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
    private function getAttributes()
    {
        $attributes_list = SecurityDevice::query()
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
