<?php

namespace App\Http\Requests;

use App\Database;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDatabaseRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('database_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'           => [
                'min:3',
                'max:32',
                'required',
                'unique:databases,name,' . request()->route('database')->id,
            ],
            'entities.*'     => [
                'integer',
            ],
            'entities'       => [
                'array',
            ],
            'informations.*' => [
                'integer',
            ],
            'informations'   => [
                'array',
            ],
            'security_need'  => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
