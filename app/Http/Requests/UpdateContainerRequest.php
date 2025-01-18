<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateContainerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('container_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:containers,name,'.request()->route('container')->id.',id,deleted_at,NULL',
            ],
            'iconFile' => ['nullable','file','mimes:png','max:65535'],
        ];
    }
}
