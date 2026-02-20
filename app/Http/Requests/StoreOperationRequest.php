<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreOperationRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('operation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('operations')->whereNull('deleted_at'),
            ],
            'actors.*' => [
                'integer',
            ],
            'actors' => [
                'array',
            ],
            'tasks.*' => [
                'integer',
            ],
            'tasks' => [
                'array',
            ],
        ];
    }
}
