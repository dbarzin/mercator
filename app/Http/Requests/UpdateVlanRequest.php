<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateVlanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('vlan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('vlans')
                    ->ignore($this->route('vlan')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
