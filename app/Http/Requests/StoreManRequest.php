<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreManRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'io_elements'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('mans')->whereNull('deleted_at'),
            ],
            'lans.*' => [
                'integer',
            ],
            'lans' => [
                'array',
            ],
        ];
    }
}
