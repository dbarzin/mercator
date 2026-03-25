<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateProcessRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'in_out'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('process_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('processes')
                    ->ignore($this->route('process')->id ?? $this->id)
                    ->whereNull('deleted_at'),
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
