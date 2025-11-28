<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Mercator\Core\Models\LogicalFlow;

class MassUpdateLogicalFlowRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('logical_flow_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // Règles du UpdateLogicalFlowRequest classique
        $updateRules = (new UpdateLogicalFlowRequest())->rules();

        // On récupère dynamiquement le nom de la table du modèle
        $model = new LogicalFlow();
        $table = $model->getTable();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            // l'id n'est pas dans UpdateLogicalFlowRequest (route model binding),
            'items.*.id' => ['required', 'integer', "exists:{${DOLLAR}table},id"],
        ];

        // On applique les règles du UpdateLogicalFlowRequest à chaque item : items.*.field
        foreach ($updateRules as $field => $rule) {
            $rules["items.*.{$field}"] = $rule;
        }

        return $rules;
    }
}

