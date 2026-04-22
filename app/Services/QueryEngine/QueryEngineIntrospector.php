<?php

namespace App\Services\QueryEngine;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class QueryEngineIntrospector
{
    protected const MODEL_NAMESPACE = 'App\\Models\\';

    /**
     * Résout la classe depuis un nom court OU une FQCN — utile
     * lors de la traversée récursive où on a déjà la classe complète.
     */
    public static function resolveModelClassFromAny(string $classOrShortName): string
    {
        // FQCN direct (ex: App\Models\LogicalServer)
        if (class_exists($classOrShortName)) {
            return $classOrShortName;
        }

        // Nom court PascalCase — usage interne depuis getRelations() (class_basename)
        $fqcn = self::MODEL_NAMESPACE . $classOrShortName;
        if (class_exists($fqcn)) {
            return $fqcn;
        }

        // Slug API en dernier recours
        return self::resolveModelClass($classOrShortName);
    }

    /**
     * Décrit un modèle : colonnes + relations.
     * Utilisé par le endpoint /query-engine/schema/{model}.
     */
    public static function describe(string $modelName): array
    {
        $class    = self::resolveModelClass($modelName);
        $instance = new $class;

        return [
            'model'     => $modelName,
            'table'     => $instance->getTable(),
            'fields'    => self::getFillable($class),
            'relations' => self::getRelations($class),
        ];
    }

    /**
     * Découverte des relations Eloquent par Reflection.
     * Le nom exposé est en snake_case (ex: logicalServers → logical_servers).
     */
    public static function getRelations(string $class): array
    {
        $instance  = new $class;
        $relations = [];

        foreach ((new ReflectionClass($instance))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->class !== $class) {
                continue;
            }
            if ($method->getNumberOfParameters() !== 0) {
                continue;
            }

            try {
                $result = $method->invoke($instance);

                if ($result instanceof Relation) {
                    $relations[] = [
                        'name'    => Str::snake($method->getName()),   // logical_servers
                        'method'  => $method->getName(),               // logicalServers (usage interne)
                        'type'    => class_basename($result),
                        'related' => class_basename($result->getRelated()),
                    ];
                }
            } catch (\Throwable) {
                continue;
            }
        }

        return $relations;
    }

    /**
     * Résout le nom de méthode Eloquent depuis un nom snake_case ou camelCase.
     * Ex : logical_servers → logicalServers
     * Lance une HttpException 422 si la relation est introuvable.
     */
    public static function resolveRelationMethod(string $class, string $relationName): string
    {
        $snake = Str::snake($relationName); // normalise l'entrée dans tous les cas

        foreach (self::getRelations($class) as $relation) {
            if ($relation['name'] === $snake) {
                return $relation['method'];
            }
        }

        abort(422, "Relation [{$relationName}] introuvable sur [" . class_basename($class) . "].");
    }

    /**
     * Retourne les champs autorisés d'un modèle :
     * fillable + id + clés étrangères déclarées dans la table.
     */
    public static function getFillable(string $class): array
    {
        $instance = new $class;
        $fillable = $instance->getFillable();

        // Toujours autoriser id et les clés primaires
        return array_unique(array_merge(['id'], $fillable));
    }

    /**
     * Vérifie qu'un champ existe dans le fillable du modèle.
     * Lance une HttpException 422 si invalide.
     */
    public static function validateField(string $class, string $field): void
    {
        $instance = new $class;
        $table    = $instance->getTable();
        $allowed  = self::getFillable($class);

        abort_if(
            ! in_array($field, $allowed, true),
            422,
            "Le champ [{$field}] n'existe pas dans la table [{$table}]."
        );
    }

    /**
     * Convertit un nom de classe modèle en nom d'API (slug pluriel).
     * Miroir de la logique d'ImportController::export().
     */
    public static function modelToApiName(string $modelName): string
    {
        return $modelName === 'MApplication'
            ? 'applications'
            : Str::plural(Str::snake($modelName, '-'));
    }

    /**
     * Résout le nom de classe court depuis un nom d'API (slug).
     * Inverse de modelToApiName().
     */
    public static function apiNameToModelName(string $apiName): string
    {
        foreach (self::listModelClasses() as $modelName) {
            if (self::modelToApiName($modelName) === $apiName) {
                return $modelName;
            }
        }

        abort(404, "Modèle API [{$apiName}] introuvable.");
    }

    /**
     * Résout la classe depuis un nom court OU un slug d'API.
     */
    public static function resolveModelClass(string $modelName): string
    {
        // FQCN interne uniquement (traversée récursive)
        if (str_contains($modelName, '\\')) {
            abort_if(! class_exists($modelName), 404, "Classe [{$modelName}] introuvable.");
            return $modelName;
        }

        // Slug API uniquement — le nom de classe court est interdit
        $short = self::apiNameToModelName($modelName); // abort 404 si inconnu
        return self::MODEL_NAMESPACE . $short;
    }

    /**
     * Liste interne des noms de classes (usage : apiNameToModelName, listModels).
     */
    protected static function listModelClasses(): array
    {
        $path    = base_path('app/Models');
        $classes = [];

        foreach (glob("{$path}/*.php") as $file) {
            $modelName = basename($file, '.php');
            $class     = self::MODEL_NAMESPACE . $modelName;

            if (! class_exists($class)) {
                continue;
            }
            if ((new ReflectionClass($class))->isAbstract()) {
                continue;
            }

            $classes[] = $modelName;
        }

        return $classes;
    }

    /**
     * Retourne les noms d'API (slugs) pour tous les modèles concrets.
     * Ex : MApplication → applications, LogicalServer → logical-servers
     */
    public static function listModels(): array
    {
        $models = array_map(
            fn (string $modelName) => self::modelToApiName($modelName),
            self::listModelClasses()
        );

        sort($models);

        return $models;
    }
}