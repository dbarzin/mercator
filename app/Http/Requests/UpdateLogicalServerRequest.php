<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateLogicalServerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('logical_server_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('logical_servers')
                    ->ignore($this->route('logical_server')->id ?? $this->id)
                    ->whereNull('deleted_at'),
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
        ];
    }
}
