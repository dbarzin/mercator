<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreSecurityControlRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('security_controls_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:255',
                'required',
                'unique:security_controls,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
