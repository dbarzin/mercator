<?php


namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('application_services')->whereNull('deleted_at'),
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
