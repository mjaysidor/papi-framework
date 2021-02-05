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
                            'id' => $id,
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

    public function create(array $data): bool|int
    {
        $result = $this->handler->insert(
            $this->getTableName(),
            $data
        );

        if ($result instanceof \PDOStatement) {
            return $this->handler->id();
        }

        return false;
    }

    public function update(
        array $where,
        array $data
    ): int {
        return $this->handler->update(
            $this->getTableName(),
            $data,
            $where
        )
                             ->rowCount()
            ;
    }

    public function updateById(
        $id,
        array $data
    ): int {
        return $this->update(
            [
                'id' => $id,
            ],
            $data
        );
    }

    public function deleteById(
        $id
    ): int {
        return $this->handler->delete(
            $this->getTableName(),
            [
                'id' => $id,
            ]
        )
                             ->rowCount()
            ;
    }
}