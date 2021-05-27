<?php

namespace App\Http\Requests;

use App\DomaineAd;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreDomaineAdRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('domaine_ad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'            => [
                'min:3',
                'max:32',
                'required',
                //'unique:domaine_ads',
                'unique:domaine_ads,name,NULL,id,deleted_at,NULL',
            ],
            'domain_ctrl_cnt' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'user_count'      => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'machine_count'   => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
