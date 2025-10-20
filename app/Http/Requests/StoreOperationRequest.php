<?php


namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreOperationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('operation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
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
