<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySecurityDeviceRequest;
use App\Http\Requests\MassStoreSecurityDeviceRequest;
use App\Http\Requests\MassUpdateSecurityDeviceRequest;
use App\Http\Requests\StoreSecurityDeviceRequest;
use App\Http\Requests\UpdateSecurityDeviceRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Mercator\Core\Models\SecurityDevice;
use Symfony\Component\HttpFoundation\Response;

class SecurityDeviceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('security_device_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = SecurityDevice::query();

        // Champs explicitement autorisés pour le filtrage
        $allowedFields = array_merge(
            SecurityDevice::$searchable ?? [],
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

        $securityDevices = $query->get();

        return response()->json($securityDevices);
    }

    public function store(StoreSecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('security_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /** @var SecurityDevice $securityDevice */
        $securityDevice = SecurityDevice::query()->create($request->all());
        // syncs
        // $securityDevice->roles()->sync($request->input('roles', []));

        return response()->json($securityDevice, Response::HTTP_CREATED);
    }

    public function show(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($securityDevice);
    }

    public function update(UpdateSecurityDeviceRequest $request, SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevice->update($request->all());
        // syncs
        // if ($request->has('roles')) {
        //     $securityDevice->roles()->sync($request->input('roles', []));
        // }

        return response()->json();
    }

    public function destroy(SecurityDevice $securityDevice)
    {
        abort_if(Gate::denies('security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $securityDevice->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroySecurityDeviceRequest $request)
    {
        abort_if(Gate::denies('security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        SecurityDevice::whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreSecurityDeviceRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `security_device_create`
        $data = $request->validated();

        $createdIds          = [];
        $securityDeviceModel = new SecurityDevice();
        $fillable            = $securityDeviceModel->getFillable();

        foreach ($data['items'] as $item) {
            // Colonnes du modèle uniquement (sans relations)
            $attributes = collect($item)
                ->only($fillable)
                ->toArray();

            /** @var SecurityDevice $securityDevice */
            $securityDevice = SecurityDevice::query()->create($attributes);

            // syncs relations éventuelles ici si nécessaire (ex: roles)
            // if (array_key_exists('roles', $item)) {
            //     $securityDevice->roles()->sync($item['roles'] ?? []);
            // }

            $createdIds[] = $securityDevice->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateSecurityDeviceRequest $request)
    {
        // L’authorize() du FormRequest gère déjà la permission `security_device_edit`
        $data               = $request->validated();
        $securityDeviceModel = new SecurityDevice();
        $fillable            = $securityDeviceModel->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id = $rawItem['id'];

            /** @var SecurityDevice $securityDevice */
            $securityDevice = SecurityDevice::query()->findOrFail($id);

            // Colonnes du modèle uniquement (sans id ni relations)
            $attributes = collect($rawItem)
                ->except(['id'])
                ->only($fillable)
                ->toArray();

            if (! empty($attributes)) {
                $securityDevice->update($attributes);
            }

            // syncs relations éventuelles ici si nécessaire (ex: roles)
            // if (array_key_exists('roles', $rawItem)) {
            //     $securityDevice->roles()->sync($rawItem['roles'] ?? []);
            // }
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
