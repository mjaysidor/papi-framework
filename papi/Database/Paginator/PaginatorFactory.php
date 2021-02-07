<?php
declare(strict_types=1);

namespace papi\Database\Paginator;

use papi\Exception\NotImplementedException;

class PaginatorFactory
{
    public static function getPaginator(
        int $paginationType,
        array &$filters,
        int $items
    ): Paginator {
        switch ($paginationType) {
            case Paginator::CURSOR_PAGINATION:
                return self::getCursorPaginator($filters, $items);
            case Paginator::OFFSET_PAGINATION:
                return self::getOffsetPaginator($filters, $items);
        }

        throw new NotImplementedException();
    }

    private static function getCursorPaginator(
        array &$filters,
        int $items
    ): CursorPaginator {
        $cursor = $filters['cursor'] ?? '';
        $column = $filters['orderBy'] ?? 'id';
        $order = $filters['order'] ?? 'ASC';
        unset($filters['cursor'], $filters['order'], $filters['orderBy']);

        return new CursorPaginator($cursor, $column, $order, $items);
    }

    private static function getOffsetPaginator(
        array &$filters,
        int $items
    ): CursorPaginator {
        throw new NotImplementedException();
    }
}