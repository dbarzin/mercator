<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\MassDestroyPhysicalSecurityDeviceRequest;
use App\Http\Requests\MassStorePhysicalSecurityDeviceRequest;
use App\Http\Requests\MassUpdatePhysicalSecurityDeviceRequest;
use App\Http\Requests\StorePhysicalSecurityDeviceRequest;
use App\Http\Requests\UpdatePhysicalSecurityDeviceRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\PhysicalSecurityDevice;
use Symfony\Component\HttpFoundation\Response;

class PhysicalSecurityDeviceController extends APIController
{
    protected string $modelClass = PhysicalSecurityDevice::class;

    public function index(Request $request)
    {
        abort_if(Gate::denies('physical_security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->indexResource($request);
    }

    public function store(StorePhysicalSecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('physical_security_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var PhysicalSecurityDevice $device */
        $device = PhysicalSecurityDevice::query()->create($request->all());

        return response()->json($device, 201);
    }

    public function show(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($physicalSecurityDevice);
    }

    public function update(UpdatePhysicalSecurityDeviceRequest $request, PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevice->update($request->all());

        return response()->json();
    }

    public function destroy(PhysicalSecurityDevice $physicalSecurityDevice)
    {
        abort_if(Gate::denies('physical_security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSecurityDevice->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyPhysicalSecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('physical_security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PhysicalSecurityDevice::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStorePhysicalSecurityDeviceRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `physical_security_device_create`
        $data = $request->validated();

        $createdIds             = [];
        $deviceModel            = new PhysicalSecurityDevice();
        $fillable               = $deviceModel->getFillable();

        foreach ($data['items'] as $item) {
            // Colonnes du modèle uniquement
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var PhysicalSecurityDevice $device */
            $device = PhysicalSecurityDevice::query()->create($attributes);

            $createdIds[] = $device->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdatePhysicalSecurityDeviceRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `physical_security_device_edit`
        $data        = $request->validated();
        $deviceModel = new PhysicalSecurityDevice();
        $fillable    = $deviceModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var PhysicalSecurityDevice $device */
            $device = PhysicalSecurityDevice::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $device->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
