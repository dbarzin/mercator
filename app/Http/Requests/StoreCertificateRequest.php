<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreCertificateRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('certificate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('certificates')->whereNull('deleted_at'),
            ],
            'start_validity' => [
                'date',
                'nullable',
            ],
            'end_validity' => [
                'date',
                'nullable',
                // TODO: fixme
                // 'after:start_validity',
            ],
        ];
    }
}
