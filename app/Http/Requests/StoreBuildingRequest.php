<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreBuildingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('building_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:2',
                'max:32',
                'required',
                Rule::unique('buildings')->whereNull('deleted_at'),
            ],
        ];
    }
}
