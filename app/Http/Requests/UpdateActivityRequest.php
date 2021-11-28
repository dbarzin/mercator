<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateActivityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:activities,name,' . request()->route('activity')->id,
                'unique:activities,name,'.request()->route('activity')->id.',id,deleted_at,NULL',
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
