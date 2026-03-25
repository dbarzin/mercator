<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPhysicalSecurityDeviceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('physical_security_device_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'exists:physical_security_devices,id',
        ];
    }
}
