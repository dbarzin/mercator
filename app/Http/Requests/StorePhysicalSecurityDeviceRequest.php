<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StorePhysicalSecurityDeviceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('physical_security_device_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:physical_security_devices',
                'unique:physical_security_devices,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
