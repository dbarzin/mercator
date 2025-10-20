<?php


namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateLanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('lan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('lans')
                    ->ignore($this->route('lan')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
