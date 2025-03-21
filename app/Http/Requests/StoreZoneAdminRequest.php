<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class StoreZoneAdminRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('zone_admin_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('zone_admins')->whereNull('deleted_at'),
            ],
        ];
    }
}
