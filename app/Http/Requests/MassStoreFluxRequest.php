<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassStoreFluxRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('flux_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // On récupère les règles du StoreFluxRequest classique
        $storeRules = (new StoreFluxRequest())->rules();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
        ];

        // On applique les règles du StoreFluxRequest à chaque item : items.*.field
        foreach ($storeRules as $field => $rule) {
            $rules["items.*.{$field}"] = $rule;
        }

        return $rules;
    }
}

