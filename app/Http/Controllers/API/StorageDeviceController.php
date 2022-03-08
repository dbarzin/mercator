<?php

namespace App\Http\Controllers\API;

use App\StorageDevice;

use App\Http\Requests\StoreStorageDeviceRequest;
use App\Http\Requests\UpdateStorageDeviceRequest;
use App\Http\Requests\MassDestroyStorageDeviceRequest;
use App\Http\Resources\Admin\StorageDeviceResource;

use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

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

    public function show(StorageDevice $storagedevice)
    {
        abort_if(Gate::denies('storage_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StorageDeviceResource($storagedevice);
    }

    public function update(UpdateStorageDeviceRequest $request, StorageDevice $storagedevice)
    {     
        abort_if(Gate::denies('storage_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storagedevice->update($request->all());
        // syncs
        // $storagedevice->roles()->sync($request->input('roles', []));

        return response()->json();
    }

    public function destroy(StorageDevice $storagedevice)
    {
        abort_if(Gate::denies('storage_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storagedevice->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyStorageDeviceRequest $request)
    {
        StorageDevice::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}

