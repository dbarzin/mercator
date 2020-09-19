<?php

namespace App\Http\Requests;

use App\DomaineAd;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDomaineAdRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('domaine_ad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:domaine_ads,id',
        ];
    }
}
