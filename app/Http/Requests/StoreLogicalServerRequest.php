<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

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
                Rule::unique('logical_servers')->whereNull('deleted_at'),
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
                'nullable',
                new IPList(),
            ],
            'servers' => [
                'array',
            ],
        ];
    }
}
