<?php

namespace App\Http\Requests;

use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreRelationRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('relation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
            ],
            'importance' => [
                'nullable',
                'integer',
                'min:0',
                'max:4',
            ],
            'source_id' => [
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
