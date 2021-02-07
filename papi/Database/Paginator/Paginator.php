<?php
declare(strict_types=1);

namespace papi\Database\Paginator;

use papi\Relation\ManyToMany;
use papi\Resource\Resource;

abstract class Paginator
{
    public const CURSOR_PAGINATION = 1;
    public const OFFSET_PAGINATION = 2;

    abstract public function getPaginatedResults(Resource $resource, array $filters): array;

    abstract public function getPaginatedManyToManyResults(ManyToMany $relation, array $filters): array;

    abstract public function addPaginationLinks(array $response): array;
}