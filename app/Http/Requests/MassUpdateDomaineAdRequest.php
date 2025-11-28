<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Mercator\Core\Models\DomaineAd;

class MassUpdateDomaineAdRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('domaine_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // Règles du UpdateDomaineAdRequest classique
        $updateRules = (new UpdateDomaineAdRequest())->rules();

        // On récupère dynamiquement le nom de la table du modèle
        $model = new DomaineAd();
        $table = $model->getTable();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            // l'id n'est pas dans UpdateDomaineAdRequest (route model binding),
            'items.*.id' => ['required', 'integer', "exists:{${DOLLAR}table},id"],
        ];

        // On applique les règles du UpdateDomaineAdRequest à chaque item : items.*.field
        foreach ($updateRules as $field => $rule) {
            $rules["items.*.{$field}"] = $rule;
        }

        $rules['items.*.forestAds']   = ['sometimes', 'array'];
        $rules['items.*.forestAds.*'] = ['integer'];

        return $rules;
    }
}

