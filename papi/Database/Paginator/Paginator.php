<?php
declare(strict_types=1);

namespace papi\Database\Paginator;

use papi\Resource\Resource;

abstract class Paginator
{
    abstract public function getPaginatedResults(Resource $resource, array $filters): array;

    abstract protected function addPaginationLinks(array $response): array;
}