<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreZoneAdminRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('zone_admin_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
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
