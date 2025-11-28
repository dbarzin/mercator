<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Mercator\Core\Models\Network;

class MassUpdateNetworkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('network_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // Règles du UpdateNetworkRequest classique
        $updateRules = (new UpdateNetworkRequest())->rules();

        // On récupère dynamiquement le nom de la table du modèle
        $model = new Network();
        $table = $model->getTable();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            // l'id n'est pas dans UpdateNetworkRequest (route model binding),
            'items.*.id' => ['required', 'integer', "exists:{${DOLLAR}table},id"],
        ];

        // On applique les règles du UpdateNetworkRequest à chaque item : items.*.field
        foreach ($updateRules as $field => $rule) {
            $rules["items.*.{$field}"] = $rule;
        }

        $rules['items.*.roles']   = ['sometimes', 'array'];
        $rules['items.*.roles.*'] = ['integer'];

        return $rules;
    }
}

