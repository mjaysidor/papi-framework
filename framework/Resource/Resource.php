<?php
declare(strict_types=1);

namespace framework\Resource;

use framework\Database\MedooHandler;
use framework\Relation\ManyToMany;
use Medoo\Medoo;

abstract class Resource
{
    protected ?Medoo $handler;

    public function __construct()
    {
        $this->handler = MedooHandler::getDbHandler();
    }

    abstract public function getTableName(): string;

    abstract public function getFields(): array;

    abstract protected function getDefaultReadFieldsArray(): array;

    abstract protected function getEditableFieldsArray(): array;

    abstract public function getFieldValidators(): array;

    abstract protected function getRelations(): array;

    public function getDefaultReadFields(): array
    {
        return array_merge(
            $this->getDefaultReadFieldsArray(),
            $this->getRelationsColumnNames()
        );
    }

    public function getEditableFields(): array
    {
        return array_merge(
            $this->getEditableFieldsArray(),
            $this->getRelationsColumnNames(),
        );
    }

    public function getRelationsColumnNames(): array
    {
        $columnNames = [];

        foreach ($this->getRelations() as $relation) {
            if (! $relation instanceof ManyToMany) {
                $columnNames[] = $relation->getRelationFieldName();
            }
        }

        return $columnNames;
    }

    public function getMigrationColumns(): array
    {
        $columns = [];
        foreach ($this->getFields() as $name => $field) {
            $columns[$name] = $field->getProperties();
        }

        return $columns;
    }

    public function getById(
        $id,
        ?array $fields = null
    ) {
        return $this->handler->get(
            $this->getTableName(),
            $this->getSELECTFields($fields),
            [
                'id' => $id,
            ]
        );
    }

    public function get(
        ?array $filters = null,
        ?array $fields = null,
    ): bool|array {
        $this->addOrderConditions($filters);

        return $this->handler->select(
            $this->getTableName(),
            $this->getSELECTFields($fields),
            $filters
        );
    }

    private function addOrderConditions(array &$filters): void
    {
        if (isset($filters['orderBy'])) {
            $filters['ORDER'] = [
                $filters['orderBy'] => $filters['order'] ?? 'ASC',
            ];
        }
        unset($filters['order'], $filters['orderBy']);
    }

    public function create(array $data): bool|string
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

    private function getSELECTFields(?array $fields): ?array
    {
        return $fields ?? $this->getDefaultReadFields();
    }
}