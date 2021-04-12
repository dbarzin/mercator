<?php

namespace App\Http\Requests;

use App\Dnsserver;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDnsserverRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('dnsserver_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:dnsservers,name,' . request()->route('dnsserver')->id,
                'unique:dnsservers,name,'.request()->route('dnsserver')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
