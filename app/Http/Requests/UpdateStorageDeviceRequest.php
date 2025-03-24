<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class UpdateStorageDeviceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('storage_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('storage_devices')
                    ->ignore($this->route('storage_device')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
