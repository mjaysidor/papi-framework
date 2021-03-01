<?php
declare(strict_types=1);

namespace papi\Resource;

use papi\Database\PostgresDb;

abstract class Resource
{
    public function getDbHandler(): PostgresDb
    {
        return new PostgresDb();
    }

    abstract public function getTableName(): string;

    abstract public function getFields(): array;

    abstract public function getDefaultReadFields(): array;

    abstract public function getEditableFields(): array;

    abstract public function getFieldValidators(): array;

    public function getById(
        int $id,
        ?array $fields = null
    ): array {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $fields ?? $this->getDefaultReadFields(),
                        [
                            'id' => $id,
                        ]
                    )
            ;
    }

    public function get(
        ?array $filters = null,
        ?array $fields = null,
        ?string $orderBy = null,
        ?string $order = null,
        ?int $limit = null
    ): array {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $fields ?? $this->getDefaultReadFields(),
                        $filters,
                        $orderBy,
                        $order,
                        $limit
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
        int $id,
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
        int $id
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