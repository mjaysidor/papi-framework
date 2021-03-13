<?php
declare(strict_types=1);

namespace papi\Database\Paginator;

class PaginatorFactory
{
    public static function getPaginator(
        array &$filters,
        int $items
    ): Paginator {
        if (($column = $filters['orderBy'] ?? null) !== null) {
            $order = $filters['order'] ?? 'asc';
            $offset = $filters['offset'] ?? null;
            unset($filters['orderBy'], $filters['order'], $filters['offset']);

            return new OffsetPaginator($column, $items, $order, $offset);
        }

        return self::getCursorPaginator($filters, $items);
    }

    public static function getCursorPaginator(
        array &$filters,
        int $items
    ): CursorPaginator {
        $cursor = $filters['cursor'] ?? '';
        $order = $filters['order'] ?? 'asc';
        unset($filters['cursor'], $filters['order']);

        return new CursorPaginator($cursor, $order, $items);
    }
}