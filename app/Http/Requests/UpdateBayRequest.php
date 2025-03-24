<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class UpdateBayRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('bay_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('bays')
                    ->ignore($this->route('bay')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
