<?php

declare(strict_types=1);

namespace papi\Database\Paginator;

use papi\Resource\Resource;

/**
 * Handles pagination of SELECT query result
 */
abstract class Paginator
{
    /**
     * Returns paginated query result for resource queries
     *
     * @param array    $filters
     * @param bool     $cache
     * @param int|null $cacheTtl
     *
     * @return array
     */
    abstract public function getPaginatedResults(
        Resource $resource,
        array $filters,
        bool $cache = false,
        ?int $cacheTtl = 300
    ): array;

    /**
     * Adds pagination informational links, such as links to next and previous sets of items & pagination type
     *
     * @param array $response
     *
     * @return array
     */
    abstract protected function addPaginationLinks(array $response): array;
}
