<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreGatewayRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('gateway_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:gateways',
                'unique:gateways,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
