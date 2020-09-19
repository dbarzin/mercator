<?php

namespace App\Http\Requests;

use App\PhysicalServer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPhysicalServerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('physical_server_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:physical_servers,id',
        ];
    }
}
