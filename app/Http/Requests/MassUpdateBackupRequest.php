<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Mercator\Core\Models\Backup;
use Symfony\Component\HttpFoundation\Response;

class MassUpdateBackupRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('backup_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        $updateRules = (new UpdateBackupRequest())->rules();

        // On récupère dynamiquement le nom de la table du modèle
        $model = new Backup();
        $table = $model->getTable();

        $rules = [
            'items'   => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', "exists:{$table},id"],
        ];

        // On applique les règles du UpdateBackupRequest à chaque item : items.*.field
        foreach ($updateRules as $field => $rule) {
            $rules["items.*.$field"] = $rule;
        }

        return $rules;
    }
}

