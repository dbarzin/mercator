<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateApplicationServiceRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('application_service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('application_services')
                    ->ignore($this->route('application_service')->id ?? $this->id)
                    ->whereNull('deleted_at'),
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
