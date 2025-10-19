<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateWanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                Rule::unique('wans')
                    ->ignore($this->route('wan')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'mans.*' => [
                'integer',
            ],
            'mans' => [
                'array',
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
