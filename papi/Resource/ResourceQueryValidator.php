<?php
declare(strict_types=1);

namespace papi\Resource;

class ResourceQueryValidator
{
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
                )) {
                return "Invalid query: $field";
            }
        }

        return null;
    }
}