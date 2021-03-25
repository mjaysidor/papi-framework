<?php

declare(strict_types=1);

namespace papi\Resource;

/**
 * Validates request queries on resource GET endpoints
 */
class ResourceQueryValidator
{
    /**
     * Returns any existing query validation errors
     *
     * @param array    $queryFilters
     *
     * @return string|null
     */
    public function getValidationErrors(
        Resource $resource,
        array $queryFilters
    ): ?string {
        $resourceFields = $resource->getFields();

        foreach ($queryFilters as $field => $value) {
            if (! array_key_exists($field, $resourceFields)
                && ! in_array(
                    $field,
                    ['offset', 'cursor', 'order', 'orderBy']
                )
            ) {
                return "Invalid query: $field";
            }
        }

        return null;
    }
}
