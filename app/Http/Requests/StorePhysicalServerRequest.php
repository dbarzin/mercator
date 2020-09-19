<?php

namespace App\Http\Requests;

use App\PhysicalServer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StorePhysicalServerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('physical_server_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:physical_servers',
            ],
        ];
    }
}
