<?php

namespace App\Http\Requests;

use App\Dnsserver;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreDnsserverRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('dnsserver_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:dnsservers',
                'unique:dnsservers,name,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
