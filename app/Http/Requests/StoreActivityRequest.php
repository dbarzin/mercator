<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreActivityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('activity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                'unique:activities,name,NULL,id,deleted_at,NULL',

            ],
            'operations.*' => [
                'integer',
            ],
            'operations' => [
                'array',
            ],
        ];
    }
}
