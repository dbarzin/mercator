<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreLogicalServerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('logical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:logical_servers,name,NULL,id,deleted_at,NULL',
            ],
            'servers.*' => [
                'integer',
            ],
            'disk' => [
                'nullable',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'address_ip' => [
                'regex:/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:\s*,\s*(?:\d|1?\d\d|2[0-4]\d|25[0-5])(?:\.(?:\d|1?\d\d|2[0-4]\d|25[0-5])){3})*$/i',
                'nullable',
            ],
            'servers' => [
                'array',
            ],
        ];
    }
}
