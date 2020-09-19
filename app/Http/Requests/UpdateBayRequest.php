<?php

namespace App\Http\Requests;

use App\Bay;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateBayRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('bay_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:bays,name,' . request()->route('bay')->id,
            ],
        ];
    }
}
