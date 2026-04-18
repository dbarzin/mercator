<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSavedQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                       => 'required|string|max:255',
            'description'                => 'nullable|string|max:1000',
            'is_public'                  => 'boolean',

            // Structure du DSL
            'query'                      => 'required|array',
            'query.from'                 => 'required|string|alpha_dash',
            'query.select'               => 'nullable|array',
            'query.select.*'             => 'string|alpha_dash',
            'query.filters'              => 'nullable|array',
            'query.filters.*.field'      => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)?$/'],
            'query.filters.*.operator'   => 'required|string|in:=,!=,<,>,like,in,not in',
            'query.filters.*.value'      => 'required',
            'query.traverse'             => 'nullable|array',
            'query.traverse.*'           => 'string|alpha_dash',
            'query.depth'                => 'nullable|integer|min:1|max:5',
            'query.output'               => 'nullable|string|in:graph,list',
            'query.limit'                => 'nullable|integer|min:1|max:1000',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_public' => $this->boolean('is_public'),
            'user_id'   => auth()->id(),
        ]);

        // query_json est le textarea brut — on décode explicitement vers query
        $raw     = $this->input('query_json', '');
        $decoded = json_decode($raw, true);

        $this->merge([
            'query' => is_array($decoded) ? $decoded : null,
        ]);
    }
}