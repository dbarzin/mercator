<?php

namespace App\Http\Requests;

use App\SecurityDevice;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateSecurityDeviceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('security_device_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:security_devices,name,' . request()->route('security_device')->id,
                'unique:security_devices,name,'.request()->route('security_device')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
