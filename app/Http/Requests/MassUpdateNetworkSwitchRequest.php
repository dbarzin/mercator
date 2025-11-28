<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Mercator\Core\Models\NetworkSwitch;

class MassUpdateNetworkSwitchRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('network_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // Règles du UpdateNetworkSwitchRequest classique
        $updateRules = (new UpdateNetworkSwitchRequest())->rules();

        // On récupère dynamiquement le nom de la table du modèle
        $model = new NetworkSwitch();
        $table = $model->getTable();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            // l'id n'est pas dans UpdateNetworkSwitchRequest (route model binding),
            'items.*.id' => ['required', 'integer', "exists:{${DOLLAR}table},id"],
        ];

        // On applique les règles du UpdateNetworkSwitchRequest à chaque item : items.*.field
        foreach ($updateRules as $field => $rule) {
            $rules["items.*.{$field}"] = $rule;
        }

        $rules['items.*.physicalSwitches']   = ['sometimes', 'array'];
        $rules['items.*.physicalSwitches.*'] = ['integer'];

        $rules['items.*.vlans']   = ['sometimes', 'array'];
        $rules['items.*.vlans.*'] = ['integer'];

        return $rules;
    }
}

