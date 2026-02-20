<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateBuildingRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('building_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:2',
                'max:32',
                'required',
                Rule::unique('buildings')
                    ->ignore($this->route('building')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
