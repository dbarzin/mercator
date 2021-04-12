<?php

namespace App\Http\Requests;

use App\ApplicationService;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreApplicationServiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'      => [
                'min:3',
                'max:32',
                'required',
                //'unique:application_services',
                'unique:application_services,name,NULL,id,deleted_at,NULL',
                
            ],
            'modules.*' => [
                'integer',
            ],
            'modules'   => [
                'array',
            ],
        ];
    }
}
