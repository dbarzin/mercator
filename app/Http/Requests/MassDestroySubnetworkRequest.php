<?php

namespace App\Http\Requests;

use App\Subnetwork;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySubnetworkRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('subnetwork_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:subnetworks,id',
        ];
    }
}
