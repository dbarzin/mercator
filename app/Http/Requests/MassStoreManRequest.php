<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassStoreManRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // On récupère les règles du StoreManRequest classique
        $storeRules = (new StoreManRequest())->rules();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
        ];

        // On applique les règles du StoreManRequest à chaque item : items.*.field
        foreach ($storeRules as $field => $rule) {
            $rules["items.*.$field"] = $rule;
        }

        return $rules;
    }
}

