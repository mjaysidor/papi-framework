<?php

declare(strict_types=1);

namespace papi\Resource;

use papi\Database\PostgresDb;

/**
 * Defines API resource
 */
abstract class Resource
{
    /**
     * Returns db handler object
     *
     * @return PostgresDb
     */
    private function getDbHandler(): PostgresDb
    {
        return new PostgresDb();
    }

    /**
     * Returns resource database table name
     */
    abstract public function getTableName(): string;

    /**
     * Get resource fields with definitions
     * ex. 'content' => new Varchar(255)
     */
    abstract public function getFields(): array;

    /**
     * Get default fields displayed on SELECT (get) requests
     */
    abstract public function getDefaultSELECTFields(): array;

    /**
     * Get fields which are allowed to be updated by PUT requests
     */
    abstract public function getEditableFields(): array;

    /**
     * Get validators for POST (create) requests
     */
    abstract public function getPOSTValidators(): array;

    /**
     * Get validators for PUT (update) requests
     */
    abstract public function getPUTValidators(): array;

    /**
     * Gets resource by id
     *
     * @param string     $id
     * @param array|null $fields
     * @param bool       $cache
     * @param int|null   $cacheTtl
     *
     * @return array
     */
    public function getById(
        string $id,
        ?array $fields = null,
        bool $cache = false,
        ?int $cacheTtl = 300
    ): array {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $fields ?? $this->getDefaultSELECTFields(),
                        [
                            'id=' => $id,
                        ],
                        cache: $cache,
                        cacheTtl: $cacheTtl
                    )
            ;
    }

    /**
     * Gets resources
     *
     * @param array       $filters
     * @param array|null  $fields
     * @param string|null $orderBy
     * @param string|null $order
     * @param int|null    $limit
     * @param string|null $offset
     * @param bool        $cache
     * @param int|null    $cacheTtl
     *
     * @return array
     */
    public function get(
        array $filters = [],
        ?array $fields = null,
        ?string $orderBy = null,
        ?string $order = null,
        ?int $limit = null,
        ?string $offset = null,
        bool $cache = false,
        ?int $cacheTtl = 300
    ): array {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $fields ?? $this->getDefaultSELECTFields(),
                        $filters,
                        $orderBy,
                        $order,
                        $limit,
                        $offset,
                        $cache,
                        $cacheTtl
                    )
            ;
    }

    /**
     * Creates resource
     *
     * @param array $data
     *
     * @return array
     */
    public function create(array $data): array
    {
        return $this->getDbHandler()
                    ->insert(
                        $this->getTableName(),
                        $data
                    )
            ;
    }

    /**
     * Updates resource
     *
     * @param string $id
     * @param array  $data
     *
     * @return int
     */
    public function update(
        string $id,
        array $data
    ): int {
        return $this->getDbHandler()
                    ->update(
                        $this->getTableName(),
                        $data,
                        [
                            'id=' => $id,
                        ]
                    )
            ;
    }

    /**
     * Deletes object
     *
     * @param string $id
     *
     * @return int
     */
    public function delete(
        string $id
    ): int {
        return $this->getDbHandler()
                    ->delete(
                        $this->getTableName(),
                        [
                            'id=' => $id,
                        ]
                    )
            ;
    }
}
