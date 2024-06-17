<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

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
                'max:255',
                'required',
                'unique:data_processing,name,NULL,id,deleted_at,NULL',
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
