<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdatePhysicalSwitchRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('physical_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:2',
                'max:64',
                'required',
                Rule::unique('physical_switches')
                    ->ignore($this->route('physical_switch')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
