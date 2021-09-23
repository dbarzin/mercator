<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateWifiTerminalRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wifi_terminal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:wifi_terminals,name,' . request()->route('wifi_terminal')->id,
                'unique:wifi_terminals,name,'.request()->route('wifi_terminal')->id.',id,deleted_at,NULL',
            ],
            'bays.*' => [
                'integer',
            ],
            'bays' => [
                'array',
            ],
        ];
    }
}
