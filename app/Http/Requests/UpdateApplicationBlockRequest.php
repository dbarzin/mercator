<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateApplicationBlockRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                //'unique:application_blocks,name,' . request()->route('application_block')->id,
                'unique:application_blocks,name,'.request()->route('application_block')->id.',id,deleted_at,NULL',
            ],
        ];
    }
}
