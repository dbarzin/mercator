<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StoreForestAdRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('forest_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('forest_ads')->whereNull('deleted_at'),
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
