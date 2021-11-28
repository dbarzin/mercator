<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateApplicationServiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:application_services,name,' . request()->route('application_service')->id,
                'unique:application_services,name,'.request()->route('application_service')->id.',id,deleted_at,NULL',
            ],
            'modules.*' => [
                'integer',
            ],
            'modules' => [
                'array',
            ],
        ];
    }
}
