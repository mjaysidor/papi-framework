<?php

declare(strict_types=1);

namespace papi\Resource;

use papi\Relation\ManyToMany;

/**
 * Validates request queries on many to many relation GET endpoints
 */
class ManyToManyQueryValidator
{
    /**
     * Returns any existing query validation errors
     *
     * @param ManyToMany $relation
     * @param array      $queryFilters
     *
     * @return string|null
     */
    public function getValidationErrors(
        ManyToMany $relation,
        array $queryFilters
    ): ?string {
        if ($queryFilters === []) {
            return "Query must contain at least one of following parameters: " . $relation->rootResourceIdField . ", "
                   . $relation->relatedResourceIdField;
        }

        foreach ($queryFilters as $field => $value) {
            if (
                ! in_array(
                    $field,
                    ['cursor', 'order', 'orderBy', $relation->rootResourceIdField, $relation->relatedResourceIdField],
                    true
                )
            ) {
                return "Invalid query: $field";
            }
        }

        return null;
    }
}
