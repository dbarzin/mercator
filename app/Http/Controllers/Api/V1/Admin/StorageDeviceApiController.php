<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreStorageDeviceRequest;
use App\Http\Requests\UpdateStorageDeviceRequest;
use App\Http\Resources\Admin\StorageDeviceResource;
use App\StorageDevice;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StorageDeviceApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('storage_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StorageDeviceResource(StorageDevice::with(['site', 'building', 'bay'])->get());
    }

    public function store(StoreStorageDeviceRequest $request)
    {
        $storageDevice = StorageDevice::create($request->all());

        return (new StorageDeviceResource($storageDevice))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StorageDeviceResource($storageDevice->load(['site', 'building', 'bay']));
    }

    public function update(UpdateStorageDeviceRequest $request, StorageDevice $storageDevice)
    {
        $storageDevice->update($request->all());

        return (new StorageDeviceResource($storageDevice))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevice->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
