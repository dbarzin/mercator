<?php

namespace App\Http\Requests;

use App\Relation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreRelationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('relation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'           => [
                'min:3',
                'max:32',
                'required',
            ],
            'importance'     => [
                'nullable',
                'integer',
                'min:0',
                'max:4',
            ],
            'source_id'      => [
                'required',
                'integer',
            ],
            'destination_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
