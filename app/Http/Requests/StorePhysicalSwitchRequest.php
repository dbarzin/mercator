<?php

namespace App\Http\Requests;

use App\PhysicalSwitch;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StorePhysicalSwitchRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('physical_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:2',
                'max:32',
                'required',
                'unique:physical_switches',
            ],
        ];
    }
}
