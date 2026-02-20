<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateMacroProcessusRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'io_elements'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('macro_processus_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('macro_processuses')
                    ->ignore($this->route('macro_processus')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'security_need' => [
                'nullable',
                'integer',
                'min:0',
                'max:5',
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
