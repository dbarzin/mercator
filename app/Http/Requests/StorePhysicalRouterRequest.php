<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StorePhysicalRouterRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('physical_router_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('physical_routers')->whereNull('deleted_at'),
            ],
            'vlans.*' => [
                'integer',
            ],
            'vlans' => [
                'array',
            ],
        ];
    }
}
