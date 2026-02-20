<?php

namespace App\Http\Requests;

use App\Rules\Cidr;
use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreSubnetworkRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];
    public function authorize() : bool
    {
        abort_if(Gate::denies('subnetwork_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('subnetworks')->whereNull('deleted_at'),
            ],
            'address' => [
                'nullable',
                new Cidr,
            ],
            'default_gateway' => [
                'nullable',
                'ip',
            ],
        ];
    }
}
