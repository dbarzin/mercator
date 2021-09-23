<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateNetworkSwitchRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('network_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:network_switches,name,' . request()->route('network_switch')->id,
                'unique:network_switches,name,'.request()->route('network_switche')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
