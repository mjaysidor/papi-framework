<?php
declare(strict_types=1);

namespace papi\Resource;

use papi\Database\PostgresDb;

abstract class Resource
{
    private function getDbHandler(): PostgresDb
    {
        return new PostgresDb();
    }

    abstract public function getTableName(): string;

    abstract public function getFields(): array;

    abstract public function getDefaultSELECTFields(): array;

    abstract public function getEditableFields(): array;

    abstract public function getFieldValidators(): array;

    public function getById(
        string $id,
        ?array $fields = null
    ): array {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $fields ?? $this->getDefaultSELECTFields(),
                        [
                            'id=' => $id,
                        ]
                    )
            ;
    }

    public function get(
        array $filters = [],
        ?array $fields = null,
        ?string $orderBy = null,
        ?string $order = null,
        ?int $limit = null,
        ?string $offset = null
    ): array {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $fields ?? $this->getDefaultSELECTFields(),
                        $filters,
                        $orderBy,
                        $order,
                        $limit,
                        $offset
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
