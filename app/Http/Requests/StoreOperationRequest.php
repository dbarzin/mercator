<?php

namespace App\Http\Requests;

use App\Operation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreOperationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('operation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'     => [
                'min:3',
                'max:32',
                'required',
                //'unique:operations',
                'unique:operations,name,NULL,id,deleted_at,NULL',
            ],
            'actors.*' => [
                'integer',
            ],
            'actors'   => [
                'array',
            ],
            'tasks.*'  => [
                'integer',
            ],
            'tasks'    => [
                'array',
            ],
        ];
    }
}
