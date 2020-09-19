<?php

namespace App\Http\Requests;

use App\PhysicalSwitch;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePhysicalSwitchRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('physical_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:2',
                'max:32',
                'required',
                'unique:physical_switches,name,' . request()->route('physical_switch')->id,
            ],
        ];
    }
}
