<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateForestAdRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('forest_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('forest_ads')
                    ->ignore($this->route('forest_ad')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'domaines.*' => [
                'integer',
            ],
            'domaines' => [
                'array',
            ],
        ];
    }
}
