<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreApplicationModuleRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];
    public function authorize() : bool
    {
        abort_if(Gate::denies('application_module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('application_modules')->whereNull('deleted_at'),
            ],
        ];
    }
}
