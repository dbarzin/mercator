<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreProcessRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('process_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                'unique:processes,name,NULL,id,deleted_at,NULL',
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
