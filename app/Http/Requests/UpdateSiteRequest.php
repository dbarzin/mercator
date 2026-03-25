<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateSiteRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('site_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('sites')
                    ->ignore($this->route('site')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'iconFile' => ['nullable', 'file', 'mimes:png', 'max:65535'],
        ];
    }
}
