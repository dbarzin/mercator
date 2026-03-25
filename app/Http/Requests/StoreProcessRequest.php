<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreProcessRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'in_out'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('processes')->whereNull('deleted_at'),
            ],
            'activities.*' => [
                'integer',
            ],
            'activities' => [
                'array',
            ],
            'entities.*' => [
                'integer',
            ],
            'entities' => [
                'array',
            ],
            'security_need' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
