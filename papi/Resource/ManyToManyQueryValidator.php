<?php

declare(strict_types=1);

namespace papi\Resource;

use papi\Relation\ManyToMany;

class ManyToManyQueryValidator
{
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
