<?php

namespace App\Http\Requests;

// app/Http/Requests/BaseFormRequest.php
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Champs contenant du HTML riche (CKEditor)
     * À surcharger dans les FormRequest enfants
     */
    protected array $htmlFields = [];

    protected function prepareForValidation(): void
    {
        $sanitized = [];

        foreach ($this->all() as $key => $value) {
            if (!is_string($value)) {
                $sanitized[$key] = $value;
                continue;
            }

            if (in_array($key, $this->htmlFields)) {
                // Champ HTML riche : sanitiser en conservant les balises sûres
                $sanitized[$key] = clean($value); // helper de mews/purifier
            } else {
                // Champ texte : supprimer toutes les balises
                $sanitized[$key] = strip_tags($value);
            }
        }

        $this->merge($sanitized);
    }
}
