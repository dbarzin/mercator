<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class UpdateApplicationModuleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_module_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('application_modules')
                    ->ignore($this->route('application_module')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
