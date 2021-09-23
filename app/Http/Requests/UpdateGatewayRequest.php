<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateGatewayRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('gateway_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:gateways,name,' . request()->route('gateway')->id,
                'unique:gateways,name,'.request()->route('gateway')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
