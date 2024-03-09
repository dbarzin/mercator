<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
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
                //'unique:forest_ads',
                'unique:forest_ads,name,NULL,id,deleted_at,NULL',
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
