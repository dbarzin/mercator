<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateApplicationBlockRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('application_blocks')
                    ->ignore($this->route('application_block')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
