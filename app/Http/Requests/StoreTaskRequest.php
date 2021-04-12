<?php

namespace App\Http\Requests;

use App\Task;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('task_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'nom' => [
                'min:3',
                'max:32',
                'required',
                //'unique:tasks',
                'unique:tasks,nom,NULL,id,deleted_at,NULL',
            ],
        ];
    }
}
