<?php

namespace App\Http\Requests;

use App\ApplicationModule;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

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
                // 'unique:application_modules,name,' . request()->route('application_module')->id,
                'unique:application_modules,name,'.request()->route('application_module')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
