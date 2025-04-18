<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreRouterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('routers')->whereNull('deleted_at'),
            ],
            'physicalRouters.*' => [
                'integer',
            ],
            'physicalRouters' => [
                'array',
            ],
            'ip_addresses' => [
                'nullable',
                new IPList(),
            ],
        ];
    }
}
