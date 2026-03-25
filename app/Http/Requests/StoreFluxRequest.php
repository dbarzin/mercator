<?php

namespace App\Http\Requests;

use Gate;
use Symfony\Component\HttpFoundation\Response;

class StoreFluxRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('flux_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => ['min:3', 'max:64', 'required'],
        ];
    }
}
