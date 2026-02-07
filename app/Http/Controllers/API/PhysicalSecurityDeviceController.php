<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

class PhysicalSecurityDeviceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('physical_security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = PhysicalSecurityDevice::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            PhysicalSecurityDevice::$searchable ?? [],
            ['id'] // Ajouter ici d’autres champs explicitement autorisés si nécessaire
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

        $devices = $query->get();

        return response()->json($devices);
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
