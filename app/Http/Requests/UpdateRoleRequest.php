<?php


namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('roles')
                    ->ignore($this->route('role')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'permissions.*' => [
                'integer',
            ],
            'permissions' => [
                'array',
            ],
        ];
    }
}
