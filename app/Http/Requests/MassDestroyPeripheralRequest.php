<?php

namespace App\Http\Requests;

use App\Peripheral;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPeripheralRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('peripheral_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:peripherals,id',
        ];
    }
}
