<?php

declare(strict_types=1);

namespace papi\Database\Paginator;

use papi\Resource\Resource;

abstract class Paginator
{
    abstract public function getPaginatedResults(
        Resource $resource,
        array $filters,
        bool $cache = false,
        ?int $cacheTtl = 300
    ): array;

    abstract protected function addPaginationLinks(array $response): array;
}
