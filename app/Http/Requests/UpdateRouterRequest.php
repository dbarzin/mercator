<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateRouterRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'rules'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('routers')
                    ->ignore($this->route('router')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'ip_addresses' => [
                'nullable',
                new IPList,
            ],
        ];
    }
}
