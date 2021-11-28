<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreLanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('lan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:lans',
                'unique:lans,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
