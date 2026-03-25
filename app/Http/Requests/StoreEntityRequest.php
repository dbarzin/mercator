<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreEntityRequest extends BaseFormRequest
{

    protected array $htmlFields = ['description', 'security_level', 'contact_point'];

    public function authorize(): bool
    {
        abort_if(Gate::denies('entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('entities')->whereNull('deleted_at'),
            ],
            'iconFile' => ['nullable', 'file', 'mimes:png', 'max:65535'],
            'seurity_level' => [
                'nullable',
                'integer',
                'min:0',
                'max:5',
            ],
        ];
    }
}
