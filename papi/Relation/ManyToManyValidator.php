<?php

declare(strict_types=1);

namespace papi\Relation;

/**
 * Validates POST/PUT request body on many to many relation endpoints based on relation config & validators
 */
class ManyToManyValidator
{
    /**
     * Returns any existing validation errors
     *
     * @param ManyToMany $relation
     * @param array      $data
     *
     * @return string|null
     */
    public function getValidationErrors(
        ManyToMany $relation,
        array $data
    ): ?string {
        if (
            ! (array_key_exists($relation->rootResourceIdField, $data)
               && array_key_exists($relation->relatedResourceIdField, $data)
            )
        ) {
            return 'Both IDs must be specified in the request body: ' .
                   "$relation->rootResourceIdField, $relation->relatedResourceIdField";
        }

        foreach ($data as $field => $value) {
            if (! in_array($field, [$relation->rootResourceIdField, $relation->relatedResourceIdField], true)) {
                return "Invalid field: $field";
            }
        }

        return null;
    }
}
