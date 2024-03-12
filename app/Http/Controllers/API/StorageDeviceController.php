<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStorageDeviceRequest;
use App\Http\Requests\StoreStorageDeviceRequest;
use App\Http\Requests\UpdateStorageDeviceRequest;
use App\Http\Resources\Admin\StorageDeviceResource;
use App\StorageDevice;
use Gate;
use Illuminate\Http\Response;

class StorageDeviceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('storage_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storagedevices = StorageDevice::all();

        return response()->json($storagedevices);
    }

    public function store(StoreStorageDeviceRequest $request)
    {
        abort_if(Gate::denies('storage_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storagedevice = StorageDevice::create($request->all());
        // syncs
        // $storagedevice->roles()->sync($request->input('roles', []));

        return response()->json($storagedevice, 201);
    }

    public function show(int $id)
    {
        abort_if(Gate::denies('storage_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storagedevice = Storagedevice::where('id', $id)->get();

        return new StorageDeviceResource($storagedevice);
    }

    public function update(UpdateStorageDeviceRequest $request, StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevice->update($request->all());
        // syncs
        // $storageDevice->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevice->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyStorageDeviceRequest $request)
    {
        abort_if(Gate::denies('storage_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        StorageDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
