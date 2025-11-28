<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassStoreDomaineAdRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('domaine_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // On récupère les règles du StoreDomaineAdRequest classique
        $storeRules = (new StoreDomaineAdRequest())->rules();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
        ];

        // On applique les règles du StoreDomaineAdRequest à chaque item : items.*.field
        foreach ($storeRules as $field => $rule) {
            $rules["items.*.{$field}"] = $rule;
        }

        $rules['items.*.forestAds']   = ['sometimes', 'array'];
        $rules['items.*.forestAds.*'] = ['integer'];

        return $rules;
    }
}

