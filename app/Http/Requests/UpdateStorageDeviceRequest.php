<?php

namespace App\Http\Requests;

use App\StorageDevice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

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
                'unique:storage_devices,name,' . request()->route('storage_device')->id,
            ],
        ];
    }
}
