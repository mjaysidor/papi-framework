<?php
declare(strict_types=1);

namespace papi\Database\Paginator;

class PaginatorFactory
{
    public static function getPaginator(
        int $paginationType,
        array &$filters
    ): Paginator {
        switch ($paginationType) {
            case Paginator::CURSOR_PAGINATION:
                return self::getCursorPaginator($filters);
            case Paginator::OFFSET_PAGINATION:
                return self::getOffsetPaginator($filters);
        }

        throw new \RuntimeException('Not implemented yet!');
    }

    private static function getCursorPaginator(
        array &$filters
    ): CursorPaginator {
        $cursor = $filters['cursor'] ?? '';
        $column = $filters['orderBy'] ?? 'id';
        $order = $filters['order'] ?? 'ASC';
        $limit = isset($filters['limit']) ? (int)$filters['limit'] : 10;
        unset($filters['cursor'], $filters['order'], $filters['orderBy'], $filters['limit']);

        return new CursorPaginator($cursor, $column, $order, $limit);
    }

    private static function getOffsetPaginator(
        array &$filters
    ): CursorPaginator {
        throw new \RuntimeException('Not implemented yet!');
    }
}