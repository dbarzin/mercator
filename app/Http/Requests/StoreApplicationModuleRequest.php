<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class StoreApplicationModuleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_module_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('application_modules')->whereNull('deleted_at'),
            ],
        ];
    }
}
