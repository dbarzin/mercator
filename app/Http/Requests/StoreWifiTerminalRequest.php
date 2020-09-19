<?php

namespace App\Http\Requests;

use App\WifiTerminal;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreWifiTerminalRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wifi_terminal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'   => [
                'min:3',
                'max:32',
                'required',
                'unique:wifi_terminals',
            ],
            'bays.*' => [
                'integer',
            ],
            'bays'   => [
                'array',
            ],
        ];
    }
}
