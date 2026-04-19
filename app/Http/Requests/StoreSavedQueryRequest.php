<?php

namespace App\Http\Requests;

use App\Services\QueryEngine\QueryDslValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreSavedQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public'   => 'boolean',
            // 'present' accepte un array même vide, contrairement à 'required'
            // La présence de 'from' est vérifiée dans withValidator via QueryDslValidator
            'query'       => 'present|array',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {   // $validator doit être reçu en paramètre
            $query = $this->input('query');
            if (! is_array($query) || empty($query)) {
                $validator->errors()->add('query', 'Le DSL de la requête est invalide ou vide.');
                return;
            }
            try {
                QueryDslValidator::validate($query);
            } catch (ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    $validator->errors()->add("query.{$field}", implode(' ', $messages));
                }
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_public' => $this->boolean('is_public'),
            'user_id'   => auth()->id(),
        ]);

        $raw     = $this->input('query_json', '');
        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // JSON invalide — on laisse la validation échouer proprement
            $this->merge(['query' => null]);
            return;
        }

        $this->merge([
            'query' => is_array($decoded) ? $decoded : null,
        ]);
    }
}