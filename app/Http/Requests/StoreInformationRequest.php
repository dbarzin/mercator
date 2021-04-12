<?php

namespace App\Http\Requests;

use App\Information;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreInformationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('information_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'          => [
                'min:3',
                'max:32',
                'required',
                //'unique:information',
                'unique:information,name,NULL,id,deleted_at,NULL',
            ],
            'processes.*'   => [
                'integer',
            ],
            'processes'     => [
                'array',
            ],
            'security_need' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
