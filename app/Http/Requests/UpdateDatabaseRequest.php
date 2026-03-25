<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateDatabaseRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize() : bool
    {
        abort_if(Gate::denies('database_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('databases')
                    ->ignore($this->route('database')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'entities.*' => [
                'integer',
            ],
            'entities' => [
                'array',
            ],
            'informations.*' => [
                'integer',
            ],
            'informations' => [
                'array',
            ],
            'security_need' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
