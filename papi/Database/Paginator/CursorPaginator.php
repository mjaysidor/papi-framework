<?php

declare(strict_types=1);

namespace papi\Database\Paginator;

use papi\Relation\ManyToMany;
use papi\Resource\Resource;

/**
 * Handles cursor pagination
 */
class CursorPaginator extends Paginator
{
    private string $cursor;

    private string $column = 'id';

    private string $order;

    private string $cursorComparisonOperator;

    private int $limit;

    private ?array $nextCursor = null;

    private ?array $previousCursor = null;

    public function __construct(
        string $cursor = '',
        string $order = 'desc',
        int $limit = 10
    ) {
        $this->cursor = $cursor;
        $this->order = $order;
        $this->limit = $limit;
        $this->cursorComparisonOperator = $this->order === 'asc' ? 'gt' : 'lt';
    }

    public function getPaginatedResults(
        Resource $resource,
        array $filters,
        bool $cache = false,
        ?int $cacheTtl = 300
    ): array {
        if ($this->cursor !== '') {
            $filters[$this->column] = [$this->cursorComparisonOperator => $this->cursor];
        }

        return $this->addPaginationLinks(
            (new $resource())->get(
                $filters,
                [],
                $this->column,
                $this->order,
                $this->limit + 1,
                cache: $cache,
                cacheTtl: $cacheTtl
            )
        );
    }

    /**
     * Returns paginated query result for many to many relation queries
     *
     * @param ManyToMany $relation
     * @param array      $filters
     *
     * @return array
     */
    public function getPaginatedManyToManyResults(ManyToMany $relation, array $filters): array
    {
        if ($this->cursor !== '') {
            $filters[$this->column] = [$this->cursorComparisonOperator => $this->cursor];
        }

        return $this->addPaginationLinks($relation->get($filters, $this->order, $this->limit + 1));
    }

    protected function addPaginationLinks(array $response): array
    {
        if (isset($response[0])) {
            $this->previousCursor['cursor'] = reset($response)[$this->column];
            $this->previousCursor['order'] = $this->order === 'asc' ? 'desc' : 'asc';
        } else {
            return [];
        }

        if (isset($response[$this->limit])) {
            unset($response[$this->limit]);
            $this->nextCursor['cursor'] = end($response)[$this->column];
            $this->nextCursor['order'] = $this->order;
        }

        return array_merge(
            $response,
            [
                '__pagination' => [
                    'type'                 => 'CURSOR',
                    'next_page_cursor'     => $this->nextCursor,
                    'previous_page_cursor' => $this->previousCursor,
                ],
            ]
        );
    }
}
