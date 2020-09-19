<?php

namespace App\Http\Requests;

use App\ExternalConnectedEntity;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyExternalConnectedEntityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('external_connected_entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:external_connected_entities,id',
        ];
    }
}
