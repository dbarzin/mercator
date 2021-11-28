<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreNetworkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('network_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:networks,name,NULL,id,deleted_at,NULL',
            ],
            'security_need_c' => [
                'nullable',
                'integer',
                'min:0',
                'max:4',
            ],
            'security_need_i' => [
                'nullable',
                'integer',
                'min:0',
                'max:4',
            ],
            'security_need_a' => [
                'nullable',
                'integer',
                'min:0',
                'max:4',
            ],
            'security_need_t' => [
                'nullable',
                'integer',
                'min:0',
                'max:4',
            ],
            'subnetworks.*' => [
                'integer',
            ],
            'subnetworks' => [
                'array',
            ],
        ];
    }
}
