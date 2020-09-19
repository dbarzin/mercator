<?php

namespace App\Http\Requests;

use App\Actor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreActorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('actor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                'unique:actors',
            ],
        ];
    }
}
