<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreStorageDeviceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('storage_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:storage_devices',
                'unique:storage_devices,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
