<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class StoreDataProcessingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('data_processing_register_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('data_processing')->whereNull('deleted_at'),
            ],
            'operations.*' => [
                'integer',
            ],
            'operations' => [
                'array',
            ],
            'informations.*' => [
                'integer',
            ],
            'informations' => [
                'array',
            ],
            'processes.*' => [
                'integer',
            ],
            'processes' => [
                'array',
            ],
        ];
    }
}
