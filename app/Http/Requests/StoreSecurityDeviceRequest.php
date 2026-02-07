<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreSecurityDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('security_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('security_devices')->whereNull('deleted_at'),
            ],
            'address_ip' => [
                'nullable',
                new IPList,
            ]
        ];
    }
}
