<?php

namespace App\Http\Requests;

use App\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

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
            'name'        => [
                'required',
            ],
            'email'       => [
                'required',
                //'unique:users,email,' . request()->route('user')->id,
                'unique:users,name,'.request()->route('user')->id.',id,deleted_at,NULL',
            ],
            'roles.*'     => [
                'integer',
            ],
            'roles'       => [
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
