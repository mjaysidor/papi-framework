<?php
declare(strict_types=1);

namespace papi\Relation;

use papi\Database\PostgresDb;
use papi\Resource\Field\Id;

class ManyToMany extends Relation
{
    public function getDbHandler(): PostgresDb
    {
        return new PostgresDb();
    }

    public string $rootResourceIdField;

    public string $relatedResourceIdField;

    public function __construct(
        string $rootResource,
        string $relatedResource,
        string $onUpdate = self::ON_UPDATE_CASCADE,
        string $onDelete = self::ON_DELETE_CASCADE
    ) {
        parent::__construct($rootResource, $relatedResource, $onUpdate, $onDelete);
        $this->rootResourceIdField = $this->rootTableName.'_id';
        $this->relatedResourceIdField = $this->relatedTableName.'_id';
    }

    public function getFields(): array
    {
        return [
            'id',
            $this->rootResourceIdField,
            $this->relatedResourceIdField,
        ];
    }

    public function getRelationFieldName(): ?string
    {
        return null;
    }

    public function getTableName(): string
    {
        return $this->rootTableName.'_'.$this->relatedTableName;
    }

    public function getColumnDefinitions(): array
    {
        return [
            'id'                          => (new Id())->getProperties(),
            $this->rootResourceIdField    => 'INT NOT NULL',
            $this->relatedResourceIdField => 'INT NOT NULL',
        ];
    }

    public function getForeignKeyDefinition(): array
    {
        return [
            $this->getTableName() => [
                $this->rootResourceIdField    => "REFERENCES $this->rootTableName(id) $this->onDelete $this->onUpdate",
                $this->relatedResourceIdField => "REFERENCES $this->relatedTableName(id) $this->onDelete $this->onUpdate",
            ],
        ];
    }

    public function getIndexDefinition(): array
    {
        return [
            "index FK_".$this->rootTableName.'_'.$this->relatedTableName.'_'."$this->rootTableName ON "
            .$this->getTableName()."($this->rootResourceIdField)"
            ,
            "index FK_".$this->rootTableName.'_'.$this->relatedTableName.'_'."$this->relatedTableName ON "
            .$this->getTableName()."($this->relatedResourceIdField)",
        ];
    }

    public function get(
        ?array $filters = null,
        ?string $order = null,
        ?int $limit = null
    ): array {
        return $this->getDbHandler()
                    ->select(
                        $this->getTableName(),
                        $this->getFields(),
                        $filters,
                        'id',
                        $order,
                        $limit
                    )
            ;
    }

    public function create(
        string $rootResourceId,
        string $relatedResourceId
    ): array {
        return $this->getDbHandler()
                    ->insert(
                        $this->getTableName(),
                        [
                            $this->rootResourceIdField    => $rootResourceId,
                            $this->relatedResourceIdField => $relatedResourceId,
                        ]
                    )
            ;
    }

    public function delete(
        string $rootResourceId,
        string $relatedResourceId
    ): int {
        return $this->getDbHandler()
                    ->delete(
                        $this->getTableName(),
                        [
                            $this->rootResourceIdField    => $rootResourceId,
                            $this->relatedResourceIdField => $relatedResourceId,
                        ]
                    )
            ;
    }

    public function exists(
        string $rootResourceId,
        string $relatedResourceId
    ): bool {
        return $this->getDbHandler()
                    ->exists(
                        $this->getTableName(),
                        [
                            $this->rootResourceIdField    => $rootResourceId,
                            $this->relatedResourceIdField => $relatedResourceId,
                        ]
                    )
            ;
    }
}