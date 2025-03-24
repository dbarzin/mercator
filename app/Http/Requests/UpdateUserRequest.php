<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('users')
                    ->ignore($this->route('user')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                Rule::unique('users')
                    ->ignore($this->route('user')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'required',
                'array',
            ],
            'granularity' => [
                'required',
                'nullable',
                'integer',
                'min:1',
                'max:3',
            ],
        ];
    }
}
