<?php

namespace App\Http\Requests;

use App\Rules\Cidr;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreLogicalFlowRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('logical_flow_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:0',
                'max:32',
            ],
            'source_ip_range' => [
                new Cidr(),
            ],
            'dest_ip_range' => [
                new Cidr(),
            ],
        ];
    }
}
