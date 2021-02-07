<?php
declare(strict_types=1);

namespace papi\Database\Paginator;

use papi\Relation\ManyToMany;
use papi\Resource\Resource;

class CursorPaginator extends Paginator
{
    private string $cursor;

    private string $column;

    private string $order;

    private int $limit;

    private ?array $nextCursor = null;

    private ?array $previousCursor = null;

    public function __construct(
        string $cursor = '',
        string $column = 'id',
        string $order = 'asc',
        int $limit = 10
    ) {
        $this->cursor = $cursor;
        $this->column = $column;
        $this->order = $order;
        $this->limit = $limit;
    }

    public function getPaginatedResults(Resource $resource, array $filters): array
    {
        $columnValueOperator = $this->order === 'desc' ? '>' : '<';

        if ($this->cursor) {
            $filters["$this->column$columnValueOperator"] = $this->cursor;
        }

        return $this->addPaginationLinks((new $resource())->get($filters, null, $this->column, $this->order, $this->limit+1));
    }

    public function getPaginatedManyToManyResults(ManyToMany $relation, array $filters): array
    {
        $columnValueOperator = $this->order === 'asc' ? '>' : '<';

        if ($this->cursor) {
            $filters["$this->column$columnValueOperator"] = $this->cursor;
        }

        return $this->addPaginationLinks($relation->get($filters, $this->order, $this->limit+1));
    }

    public function addPaginationLinks(array $response): array
    {
        if (isset($response[0])) {
            $this->previousCursor['cursor'] = reset($response)[$this->column];
            $this->previousCursor['order'] = $this->order === 'asc' ? 'desc' : 'asc';
        } else {
            return [];
        }

        if (isset($response[$this->limit])) {
            unset($response[array_key_last($response)]);
            $this->nextCursor['cursor'] = end($response)[$this->column];
            $this->nextCursor['order'] = $this->order;
        }

        return array_merge(
            $response,
            [
                '__pagination' => [
                    'next_page_cursor'     => $this->nextCursor,
                    'previous_page_cursor' => $this->previousCursor,
                ],
            ]
        );
    }
}