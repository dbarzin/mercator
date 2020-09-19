<?php

namespace App\Http\Requests;

use App\MacroProcessus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMacroProcessusRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('macro_processus_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:macro_processuses,id',
        ];
    }
}
