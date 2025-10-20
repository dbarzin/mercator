<?php


namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateInformationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('information_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('information')
                    ->ignore($this->route('information')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'processes.*' => [
                'integer',
            ],
            'processes' => [
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
