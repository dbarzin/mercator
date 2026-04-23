<?php

namespace App\Traits;

trait HasUniqueIdentifier
{
    public function getPrefix(): string
    {
        return self::$prefix;
    }

    public function getUID(): string
    {
        return self::$prefix . $this->id;
    }

    /**
     * Récupère l'UID d'une entité liée via un mapping field => relationName
     *
     * @param array<string, string> $relations
     */
    protected function getEntityUID(array $relations): ?string
    {
        foreach ($relations as $field => $relationName) {
            if ($this->$field !== null) {
                $related = $this->$relationName()->getRelated();

                if ($related instanceof \App\Contracts\HasPrefix) {
                    return $related::getPrefix() . $this->$field;
                }

                throw new \LogicException(
                    sprintf('Model %s must implement HasPrefix', $related::class)
                );
            }
        }

        return null;
    }
}
