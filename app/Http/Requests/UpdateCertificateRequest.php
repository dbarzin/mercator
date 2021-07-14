<?php

namespace App\Http\Requests;

use Gate;
use App\Certificate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateCertificateRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('certificate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:certificates,name,'.request()->route('certificate')->id.',id,deleted_at,NULL',
            ],
            'start_validity' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'end_validity' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
                'after:start_validity'
            ],

        ];
    }
}
