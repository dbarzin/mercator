<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class UpdatePhoneRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('phone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('phones')
                    ->ignore($this->route('phone')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'address_ip' => [
                'nullable',
                new IPList(),
            ],
        ];
    }
}
