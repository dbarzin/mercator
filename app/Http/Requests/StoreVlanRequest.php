<?php

namespace App\Http\Requests;

use App\Vlan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreVlanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('vlan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:vlans',
                'unique:vlans,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
