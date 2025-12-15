<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateFluxRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('flux_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => ['min:3', 'max:64', 'required'],
            /*
            'application_source_id' =>
                ['required_without_all:service_source_id,module_source_id,database_source_id'],
            'service_source_id' =>
                ['required_without_all:application_source_id,module_source_id,database_source_id'],
            'module_source_id' =>
                ['required_without_all:application_source_id,service_source_id,database_source_id'],
            'database_source_id' =>
                ['required_without_all:application_source_id,service_source_id,module_source_id'],
            */

        ];
    }
}
