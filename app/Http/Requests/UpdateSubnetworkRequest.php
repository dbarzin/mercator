<?php

namespace App\Http\Requests;

use App\Rules\Cidr;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateSubnetworkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('subnetwork_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:subnetworks,name,'.request()->route('subnetwork')->id.',id,deleted_at,NULL',
            ],
            'address' => [
                'nullable',
                new Cidr(),
            ],
            'default_gateway' => [
                'nullable',
                'ip',
            ],
        ];
    }
}
