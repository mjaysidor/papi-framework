<?php

declare(strict_types=1);

namespace papi\Database\Paginator;

/**
 * Returns best suited paginator based on user request
 * (Cursor paginator for queries ordered by unique id &
 * Offset paginator for queries ordered by other non-unique columns)
 */
class PaginatorFactory
{
    /**
     * Returns best suited paginator based on user request
     *
     * @param array $filters
     * @param int   $items
     *
     * @return Paginator
     */
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

    /**
     * Returns Cursor paginator object
     *
     * @param array $filters
     * @param int   $items
     *
     * @return CursorPaginator
     */
    public static function getCursorPaginator(
        array &$filters,
        int $items
    ): CursorPaginator {
        $cursor = $filters['cursor'] ?? '';
        $order = $filters['order'] ?? 'desc';
        unset($filters['cursor'], $filters['order']);

        return new CursorPaginator($cursor, $order, $items);
    }
}
