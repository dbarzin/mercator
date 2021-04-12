<?php

namespace App\Http\Requests;

use App\NetworkSwitch;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreNetworkSwitchRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('network_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:2',
                'max:32',
                'required',
                //'unique:network_switches',
                'unique:network_switches,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
