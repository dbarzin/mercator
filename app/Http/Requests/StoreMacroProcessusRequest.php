<?php

namespace App\Http\Requests;

use App\MacroProcessus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreMacroProcessusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('macro_processus_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'          => [
                'min:3',
                'max:32',
                'required',
                'unique:macro_processuses',
            ],
            'security_need' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'processes.*'   => [
                'integer',
            ],
            'processes'     => [
                'array',
            ],
        ];
    }
}
