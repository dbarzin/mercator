<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateBuildingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('building_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:2',
                'max:32',
                'required',
                Rule::unique('buildings')
                    ->ignore($this->route('building')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
