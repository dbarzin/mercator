<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateExternalConnectedEntityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('external_connected_entity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:external_connected_entities,name,'.request()->route('external_connected_entity')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
