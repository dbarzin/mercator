<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateSecurityControlRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('actor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:255',
                'required',
                'unique:security_controls,name,'.request()->route('security_control')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
