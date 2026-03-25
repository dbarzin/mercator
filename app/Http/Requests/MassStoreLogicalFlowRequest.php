<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassStoreLogicalFlowRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('logical_flow_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // On récupère les règles du StoreLogicalFlowRequest classique
        $storeRules = (new StoreLogicalFlowRequest())->rules();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
        ];

        // On applique les règles du StoreLogicalFlowRequest à chaque item : items.*.field
        foreach ($storeRules as $field => $rule) {
            $rules["items.*.$field"] = $rule;
        }

        return $rules;
    }
}

