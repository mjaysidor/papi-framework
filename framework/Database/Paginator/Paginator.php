<?php
declare(strict_types=1);

namespace framework\Database\Paginator;

use framework\Resource\Resource;

abstract class Paginator
{
    public const CURSOR_PAGINATION = 1;
    public const OFFSET_PAGINATION = 2;

    abstract public function getPaginatedResults(Resource $resource, array $filters): array;

    abstract protected function addPaginationToFilters(array $filters): array;

    abstract protected function addPaginationLinks(array $response): array;
}