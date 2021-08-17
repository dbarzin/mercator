<?php

namespace App\Http\Requests;

use App\Subnetwork;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreSubnetworkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('subnetwork_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',                
                'unique:subnetworks,name,NULL,id,deleted_at,NULL',
            ],
            'address' => [
                'regex:/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\/[0-9][0-9]?$/i',
                'nullable'
            ]
        ];
    }
}
