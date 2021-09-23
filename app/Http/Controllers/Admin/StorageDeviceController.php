<?php

namespace App\Http\Controllers\Admin;

use App\Bay;
use App\Building;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStorageDeviceRequest;
use App\Http\Requests\StoreStorageDeviceRequest;
use App\Http\Requests\UpdateStorageDeviceRequest;
use App\Site;
use App\StorageDevice;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StorageDeviceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('storage_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevices = StorageDevice::all()->sortBy('name');

        return view('admin.storageDevices.index', compact('storageDevices'));
    }

    public function create()
    {
        abort_if(Gate::denies('storage_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.storageDevices.create', compact('sites', 'buildings', 'bays'));
    }

    public function store(StoreStorageDeviceRequest $request)
    {
        $storageDevice = StorageDevice::create($request->all());

        return redirect()->route('admin.storage-devices.index');
    }

    public function edit(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bays = Bay::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $storageDevice->load('site', 'building', 'bay');

        return view('admin.storageDevices.edit', compact('sites', 'buildings', 'bays', 'storageDevice'));
    }

    public function update(UpdateStorageDeviceRequest $request, StorageDevice $storageDevice)
    {
        $storageDevice->update($request->all());

        return redirect()->route('admin.storage-devices.index');
    }

    public function show(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevice->load('site', 'building', 'bay');

        return view('admin.storageDevices.show', compact('storageDevice'));
    }

    public function destroy(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevice->delete();

        return back();
    }

    public function massDestroy(MassDestroyStorageDeviceRequest $request)
    {
        StorageDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
