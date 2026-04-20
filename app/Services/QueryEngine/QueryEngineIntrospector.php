<?php

namespace App\Services\QueryEngine;

use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;

class QueryEngineIntrospector
{
    protected const MODEL_NAMESPACE = 'Mercator\\Core\\Models\\';

    /**
     * Résout la classe d'un modèle à partir de son nom court.
     * Même logique que ImportController::resolveModelClass().
     */
    public static function resolveModelClass(string $modelName): string
    {
        $class = self::MODEL_NAMESPACE . $modelName;
        abort_if(! class_exists($class), 404, "Modèle [{$modelName}] introuvable.");
        return $class;
    }

    /**
     * Résout la classe depuis un nom court OU une FQCN — utile
     * lors de la traversée récursive où on a déjà la classe complète.
     */
    public static function resolveModelClassFromAny(string $classOrShortName): string
    {
        if (class_exists($classOrShortName)) {
            return $classOrShortName;
        }
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
     * Inspiré de ImportController::export() — étendu à tous les types.
     */
    public static function getRelations(string $class): array
    {
        $instance  = new $class;
        $relations = [];

        foreach ((new ReflectionClass($instance))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // Même filtre que ImportController
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
                        'name'    => $method->getName(),
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
    public static function listModels(): array
    {
        $path   = base_path('vendor/sourcentis/mercator-core/src/Models');
        $models = [];

        foreach (glob("{$path}/*.php") as $file) {
            $modelName = basename($file, '.php');
            $class     = self::MODEL_NAMESPACE . $modelName;

            if (! class_exists($class)) {
                continue;
            }

            $ref = new ReflectionClass($class);
            if ($ref->isAbstract()) {
                continue;
            }

            $models[] = $modelName;
        }

        sort($models);

        return $models;
    }
}