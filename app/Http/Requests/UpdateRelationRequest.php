<?php


namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateRelationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('relation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                /* Not unique
                Rule::unique('relations')
                    ->ignore($this->route('relation')->id ?? $this->id)
                    ->whereNull('deleted_at'),
                */
            ],
            'importance' => [
                'nullable',
                'integer',
                'min:0',
                'max:4',
            ],
            'source_id' => [
                'required',
                'integer',
            ],
            'destination_id' => [
                'required',
                'integer',
            ],
            'start_validity' => [
                'date',
                'nullable',
            ],
            'end_validity' => [
                'date',
                'nullable',
                // TODO: fixme
                // 'after:start_validity',
            ],
        ];
    }
}
