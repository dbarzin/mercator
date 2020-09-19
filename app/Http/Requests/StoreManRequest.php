<?php

namespace App\Http\Requests;

use App\Man;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreManRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'   => [
                'min:3',
                'max:32',
                'required',
                'unique:mans',
            ],
            'lans.*' => [
                'integer',
            ],
            'lans'   => [
                'array',
            ],
        ];
    }
}
