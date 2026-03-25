<?php

namespace App\Http\Requests;

use Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateAdminUserRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize(): bool
    {
        abort_if(Gate::denies('admin_user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'min:3',
                'max:32',
                'required',
            ],
            'firstname' => [
                'max:64',
            ],
            'lastname' => [
                'max:64',
            ],
        ];
    }
}
