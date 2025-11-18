<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateLogicalFlowRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_if(Gate::denies('logical_flow_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'min:0',
                'max:64',
            ],
        ];
    }
}
