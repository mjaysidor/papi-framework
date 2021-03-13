<?php
declare(strict_types=1);

namespace papi\Resource;

class ResourceQueryValidator
{
    public function getValidationErrors(
        Resource $resource,
        array $queryFilters
    ): ?string {
        foreach ($queryFilters as $field => $value) {
            if (! in_array($field, ['offset', 'cursor', 'order', 'orderBy'])
                && ! array_key_exists($field, $resource->getFields())
            ) {
                return "Invalid query: $field";
            }
        }

        return null;
    }
}