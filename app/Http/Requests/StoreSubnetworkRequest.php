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
                //'unique:subnetworks',
                'unique:subnetworks,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
