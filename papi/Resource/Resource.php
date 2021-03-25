<?php

declare(strict_types=1);

namespace papi\Resource;

use papi\Database\PostgresDb;

abstract class Resource
{
    /**
     * @return PostgresDb db handler object
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

    public function create(array $data): array
    {
        return $this->getDbHandler()
                    ->insert(
                        $this->getTableName(),
                        $data
                    )
            ;
    }

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
