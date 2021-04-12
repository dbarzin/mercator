<?php

namespace App\Http\Requests;

use App\MApplication;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreMApplicationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('m_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'              => [
                'min:3',
                'max:32',
                'required',
                //'unique:m_applications',
                'unique:m_applications,name,NULL,id,deleted_at,NULL',
            ],
            'entities.*'        => [
                'integer',
            ],
            'entities'          => [
                'array',
            ],
            'security_need'     => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'processes.*'       => [
                'integer',
            ],
            'processes'         => [
                'array',
            ],
            'services.*'        => [
                'integer',
            ],
            'services'          => [
                'array',
            ],
            'databases.*'       => [
                'integer',
            ],
            'databases'         => [
                'array',
            ],
            'logical_servers.*' => [
                'integer',
            ],
            'logical_servers'   => [
                'array',
            ],
        ];
    }
}
