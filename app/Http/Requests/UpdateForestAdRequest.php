<?php

namespace App\Http\Requests;

use App\ForestAd;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateForestAdRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('forest_ad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'       => [
                'min:3',
                'max:32',
                'required',
                //'unique:forest_ads,name,' . request()->route('forest_ad')->id,
                'unique:forest_ads,name,'.request()->route('forest_ad')->id.',id,deleted_at,NULL',
            ],
            'domaines.*' => [
                'integer',
            ],
            'domaines'   => [
                'array',
            ],
        ];
    }
}
