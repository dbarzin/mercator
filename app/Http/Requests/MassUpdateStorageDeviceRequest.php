<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Mercator\Core\Models\StorageDevice;

class MassUpdateStorageDeviceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('storage_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // Règles du UpdateStorageDeviceRequest classique
        $updateRules = (new UpdateStorageDeviceRequest())->rules();

        // On récupère dynamiquement le nom de la table du modèle
        $model = new StorageDevice();
        $table = $model->getTable();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            // l'id n'est pas dans UpdateStorageDeviceRequest (route model binding),
            'items.*.id' => ['required', 'integer', "exists:{${DOLLAR}table},id"],
        ];

        // On applique les règles du UpdateStorageDeviceRequest à chaque item : items.*.field
        foreach ($updateRules as $field => $rule) {
            $rules["items.*.{$field}"] = $rule;
        }

        return $rules;
    }
}

