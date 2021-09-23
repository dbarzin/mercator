<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePhoneRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('phone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:phones,name,' . request()->route('phone')->id,
                'unique:phones,name,'.request()->route('phone')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
