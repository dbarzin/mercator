<?php

namespace App\Services\QueryEngine;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Validateur dédié pour le DSL du Query Engine.
 *
 * Gère la validation récursive des filtres (groupes imbriqués),
 * les opérateurs complets, et les valeurs nullables.
 */
class QueryDslValidator
{
    protected const ALLOWED_OPERATORS = [
        '=', '!=', '<', '>', '<=', '>=',
        'like', 'not like',
        'in', 'not in',
    ];

    protected const ALLOWED_BOOLEANS = ['and', 'or', 'not'];

    protected const ALLOWED_OUTPUTS = ['graph', 'list'];

    protected array $errors = [];

    // ─────────────────────────────────────────────────────────────
    // Point d'entrée
    // ─────────────────────────────────────────────────────────────

    /**
     * Valide le DSL et retourne le tableau nettoyé.
     * Lance une ValidationException si invalide.
     */
    public static function validate(array $data): array
    {
        return new self()->doValidate($data);
    }


    protected function doValidate(array $data): array
    {
        // ── Validation des champs de premier niveau ───────────────
        $validator = Validator::make($data, [
            'from'       => ['required', 'string', 'regex:/^[a-zA-Z][a-zA-Z0-9-]*$/'],
            'select'     => ['nullable', 'array'],
            'select.*'   => ['string',   'regex:/^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*$/'],
            'fields'     => ['nullable', 'array'],
            'fields.*'   => ['string',   'regex:/^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*$/'],
            'filters'    => ['nullable', 'array'],
            'traverse'   => ['nullable', 'array'],
            'traverse.*' => ['string',   'regex:/^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*$/'],
            'depth'      => ['prohibited'],
            'output'     => ['nullable', 'string',  'in:graph,list'],
            'limit'      => ['nullable', 'integer', 'min:1', 'max:1000'],
        ], [
            'from.required' => 'Le champ "from" est obligatoire.',
            'from.regex'    => 'Le modèle "from" ne doit contenir que des lettres, chiffres et tirets.',
            'output.in'     => 'Le champ "output" doit être "graph" ou "list".',
            'depth.min'     => 'La profondeur doit être entre 1 et 5.',
            'depth.max'     => 'La profondeur doit être entre 1 et 5.',
            'limit.max'     => 'La limite ne peut pas dépasser 1000.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        // ── Validation récursive des filtres ─────────────────────
        if (! empty($data['filters'])) {
            $this->validateFilters($data['filters'], 'filters');

            if (! empty($this->errors)) {
                $validator = Validator::make([], []);
                $validator->errors()->merge($this->errors);
                throw new ValidationException($validator);
            }
        }

        $validated['filters'] = $data['filters'] ?? [];

        return $validated;
    }

    // ─────────────────────────────────────────────────────────────
    // Validation récursive des filtres
    // ─────────────────────────────────────────────────────────────

    protected function validateFilters(array $filters, string $path): void
    {
        foreach ($filters as $i => $filter) {
            $this->validateFilter($filter, "{$path}.{$i}");
        }
    }

    protected function validateFilter(mixed $filter, string $path): void
    {
        if (! is_array($filter)) {
            $this->errors["{$path}"] = ["Le filtre doit être un objet."];
            return;
        }

        // ── Groupe : { boolean, group: [...] } ───────────────────
        if (array_key_exists('group', $filter)) {
            $this->validateGroup($filter, $path);
            return;
        }

        // ── Condition : { field, operator, value, ?boolean } ─────
        $this->validateCondition($filter, $path);
    }

    protected function validateGroup(array $filter, string $path): void
    {
        // boolean est optionnel au premier niveau, obligatoire dans les groupes imbriqués
        if (isset($filter['boolean'])) {
            if (! in_array(strtolower($filter['boolean']), self::ALLOWED_BOOLEANS, true)) {
                $this->errors["{$path}.boolean"] = [
                    'Le boolean doit être "and", "or" ou "not". Obtenu : "' . $filter['boolean'] . '"'
                ];
            }
        }

        if (empty($filter['group']) || ! is_array($filter['group'])) {
            $this->errors["{$path}.group"] = ['Le groupe doit contenir au moins un filtre.'];
            return;
        }

        // Récursion
        $this->validateFilters($filter['group'], "{$path}.group");
    }

    protected function validateCondition(array $filter, string $path): void
    {
        // field
        if (empty($filter['field'])) {
            $this->errors["{$path}.field"] = ['Le champ "field" est obligatoire.'];
        } elseif (! preg_match('/^[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*$/', $filter['field'])) {
            $this->errors["{$path}.field"] = [
                'Le champ "' . $filter['field'] . '" contient des caractères invalides.'
            ];
        }

        // operator
        if (empty($filter['operator'])) {
            $this->errors["{$path}.operator"] = ['L\'opérateur est obligatoire.'];
        } elseif (! in_array(strtolower($filter['operator']), self::ALLOWED_OPERATORS, true)) {
            $this->errors["{$path}.operator"] = [
                'Opérateur interdit : "' . $filter['operator'] . '". '
                . 'Autorisés : ' . implode(', ', self::ALLOWED_OPERATORS)
            ];
        }

        // value — nullable mais obligatoire (la clé doit être présente)
        if (! array_key_exists('value', $filter)) {
            $this->errors["{$path}.value"] = ['La clé "value" est obligatoire (peut être null).'];
        } else {
            $this->validateValue($filter, $path);
        }

        // boolean optionnel
        if (isset($filter['boolean'])
            && ! in_array(strtolower($filter['boolean']), self::ALLOWED_BOOLEANS, true)) {
            $this->errors["{$path}.boolean"] = [
                'Le boolean doit être "and", "or" ou "not".'
            ];
        }
    }

    protected function validateValue(array $filter, string $path): void
    {
        $op    = strtolower($filter['operator'] ?? '');
        $value = $filter['value'];

        // IS NULL / IS NOT NULL → value doit être null
        if ($value === null) {
            if (! in_array($op, ['=', '!='], true)) {
                $this->errors["{$path}.value"] = [
                    'Une valeur null n\'est valide qu\'avec les opérateurs "=" et "!=".'
                ];
            }
            return;
        }

        // IN / NOT IN → valeur doit être un tableau non vide
        if (in_array($op, ['in', 'not in'], true)) {
            if (! is_array($value) || empty($value)) {
                $this->errors["{$path}.value"] = [
                    'L\'opérateur "' . $op . '" requiert un tableau de valeurs non vide.'
                ];
            }
            return;
        }

        // Opérateurs scalaires → pas de tableau
        if (is_array($value)) {
            $this->errors["{$path}.value"] = [
                'L\'opérateur "' . $op . '" ne supporte pas un tableau de valeurs.'
            ];
            return;
        }

        // LIKE → doit être une chaîne
        if (in_array($op, ['like', 'not like'], true) && ! is_string($value)) {
            $this->errors["{$path}.value"] = [
                'L\'opérateur "' . $op . '" requiert une chaîne de caractères.'
            ];
        }
    }
}