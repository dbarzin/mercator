<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStorageDeviceRequest;
use App\Http\Requests\MassStoreStorageDeviceRequest;
use App\Http\Requests\MassUpdateStorageDeviceRequest;
use App\Http\Requests\StoreStorageDeviceRequest;
use App\Http\Requests\UpdateStorageDeviceRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\StorageDevice;
use Symfony\Component\HttpFoundation\Response;

class StorageDeviceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('storage_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = StorageDevice::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            StorageDevice::$searchable ?? [],
            ['id'] // Ajouter ici d'autres champs explicitement autorisés si nécessaire
        );

        $params = $request->query();

        foreach ($params as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // field ou field__operator
            [$field, $operator] = array_pad(explode('__', $key, 2), 2, 'exact');

            if (! in_array($field, $allowedFields, true)) {
                continue; // Ignore les champs non autorisés
            }

            switch ($operator) {
                case 'exact':
                    $query->where($field, $value);
                    break;

                case 'contains':
                    $query->where($field, 'LIKE', '%' . $value . '%');
                    break;

                case 'startswith':
                    $query->where($field, 'LIKE', $value . '%');
                    break;

                case 'endswith':
                    $query->where($field, 'LIKE', '%' . $value);
                    break;

                case 'lt':
                    $query->where($field, '<', $value);
                    break;

                case 'lte':
                    $query->where($field, '<=', $value);
                    break;

                case 'gt':
                    $query->where($field, '>', $value);
                    break;

                case 'gte':
                    $query->where($field, '>=', $value);
                    break;

                default:
                    $query->where($field, $value);
            }
        }

        $storageDevices = $query->get();

        return response()->json($storageDevices);
    }

    public function store(StoreStorageDeviceRequest $request)
    {
        abort_if(Gate::denies('storage_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var StorageDevice $storageDevice */
        $storageDevice = StorageDevice::query()->create($request->all());

        return response()->json($storageDevice, Response::HTTP_CREATED);
    }

    public function show(StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($storageDevice);
    }

    public function update(UpdateStorageDeviceRequest $request, StorageDevice $storageDevice)
    {
        abort_if(Gate::denies('storage_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $storageDevice->update($request->all());

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

        StorageDevice::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreStorageDeviceRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `storage_device_create`
        $data = $request->validated();

        $createdIds          = [];
        $storageDeviceModel  = new StorageDevice();
        $fillable            = $storageDeviceModel->getFillable();

        foreach ($data['items'] as $item) {
            // Colonnes du modèle uniquement
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var StorageDevice $storageDevice */
            $storageDevice = StorageDevice::query()->create($attributes);

            $createdIds[] = $storageDevice->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateStorageDeviceRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `storage_device_edit`
        $data              = $request->validated();
        $storageDeviceModel = new StorageDevice();
        $fillable           = $storageDeviceModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var StorageDevice $storageDevice */
            $storageDevice = StorageDevice::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $storageDevice->update($attributes);
            }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
