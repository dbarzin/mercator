<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDataProcessingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('data_processing_register_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:data_processing,name,'.request()->route('data_processing')->id.',id,deleted_at,NULL',
            ],
            'processes.*' => [
                'integer',
            ],
            'processes' => [
                'array',
            ],
            'applications.*' => [
                'integer',
            ],
            'applications' => [
                'array',
            ],
            'informations.*' => [
                'integer',
            ],
            'informations' => [
                'array',
            ],
        ];
    }
}
