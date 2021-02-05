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
        $id,
        ?array $fields = null
    ) {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $fields ?? $this->getDefaultReadFields(),
                        [
                            'id=' => $id,
                        ]
                    )
            ;
    }

    public function get(
        ?array $filters = null,
        ?array $fields = null,
    ): bool|array {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $fields ?? $this->getDefaultReadFields(),
                        $filters
                    )
            ;
    }

    public function create(array $data): int|string
    {
        return $this->getDbHandler()
                    ->insert(
                        $this->getTableName(),
                        $data
                    )
            ;
    }

    public function update(
        $id,
        array $data
    ): int|string {
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
        $id
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