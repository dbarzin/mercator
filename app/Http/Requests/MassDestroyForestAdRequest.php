<?php

namespace App\Http\Requests;

use App\ForestAd;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyForestAdRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('forest_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:forest_ads,id',
        ];
    }
}
