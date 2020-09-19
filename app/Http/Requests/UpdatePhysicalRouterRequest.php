<?php

namespace App\Http\Requests;

use App\PhysicalRouter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdatePhysicalRouterRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('physical_router_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'vlans.*' => [
                'integer',
            ],
            'vlans'   => [
                'array',
            ],
        ];
    }
}
