<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreDhcpServerRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('dhcp_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('dhcp_servers')->whereNull('deleted_at'),
            ],
            'address_ip' => [
                'nullable',
                new IPList,
            ],
        ];
    }
}
