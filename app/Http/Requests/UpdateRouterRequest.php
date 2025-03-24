<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateRouterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('routers')
                    ->ignore($this->route('router')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'ip_addresses' => [
                'nullable',
                new IPList(),
            ],
        ];
    }
}
