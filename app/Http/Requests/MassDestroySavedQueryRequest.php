<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MassDestroySavedQueryRequest extends FormRequest
{
    public function authorize() : bool
    {
        // abort_if(Gate::denies('query_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;
    }

    public function rules() : array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'exists:saved_query,id',
        ];
    }
}
