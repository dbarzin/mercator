<?php

namespace App\Http\Requests;

use App\Flux;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreFluxRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('flux_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => 
                ['min:3','max:64','required'],            
        ];
    }
}
