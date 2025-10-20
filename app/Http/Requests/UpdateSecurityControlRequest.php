<?php


namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateSecurityControlRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('actor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:255',
                'required',
                Rule::unique('security_controls')
                    ->ignore($this->route('security_control')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
