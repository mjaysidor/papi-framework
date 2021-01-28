<?php
declare(strict_types=1);

namespace framework\Database\Paginator;

use framework\Resource\Resource;

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
        string $order = 'ASC',
        int $limit = 10
    ) {
        $this->cursor = $cursor;
        $this->column = $column;
        $this->order = $order;
        $this->limit = $limit;
    }

    public function getPaginatedResults(Resource $resource, array $filters): array
    {
        $filters = $this->addPaginationToFilters($filters);

        return $this->addPaginationLinks((new $resource())->get($filters));
    }

    public function addPaginationToFilters(array $filters): array
    {
        $columnValueOperator = $this->order === 'ASC' ? '>' : '<';

        $paginationWhereArray = [
            'LIMIT' => $this->limit + 1,
            'ORDER' => [
                $this->column => $this->order,
            ],
        ];

        if ($this->cursor !== '') {
            $paginationWhereArray["$this->column[$columnValueOperator]"] = $this->cursor;
        }

        return array_merge(
            $filters,
            $paginationWhereArray
        );
    }

    public function addPaginationLinks(array $response): array
    {
        if (count($response) > $this->limit) {
            unset($response[array_key_last($response)]);
            $this->nextCursor['cursor'] = end($response)[$this->column];
            $this->nextCursor['order'] = $this->order;
        }

        if (count($response) > 0) {
            $this->previousCursor['cursor'] = reset($response)[$this->column];
            $this->previousCursor['order'] = $this->order === 'ASC' ? 'DESC' : 'ASC';
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