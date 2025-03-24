<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

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
                Rule::unique('gateways')
                    ->ignore($this->route('gateway')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
