<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Mercator\Core\Models\Role;

class MassUpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        // Règles du UpdateRoleRequest classique
        $updateRules = (new UpdateRoleRequest())->rules();

        // On récupère dynamiquement le nom de la table du modèle
        $model = new Role();
        $table = $model->getTable();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            // l'id n'est pas dans UpdateRoleRequest (route model binding),
            'items.*.id' => ['required', 'integer', "exists:{$table},id"],
        ];

        // On applique les règles du UpdateRoleRequest à chaque item : items.*.field
        foreach ($updateRules as $field => $rule) {
            $rules["items.*.$field"] = $rule;
        }

        return $rules;
    }
}

