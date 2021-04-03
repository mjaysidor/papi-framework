<?php

declare(strict_types=1);

namespace papi\Relation;

use papi\Database\PostgresDb;
use papi\Resource\Field\Id;

class ManyToMany extends Relation
{
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
                $this->rootResourceIdField => "REFERENCES $this->rootTableName(id) $this->onDelete $this->onUpdate",
                $this->relatedResourceIdField
                                           => "REFERENCES $this->relatedTableName(id) $this->onDelete $this->onUpdate",
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

    /**
     * Get relation objects from database
     *
     * @param PostgresDb $dbConnection
     * @param array      $filters
     * @param string     $order
     * @param int|null   $limit
     *
     * @return array
     */
    public function get(
        PostgresDb $dbConnection,
        array $filters = [],
        string $order = 'desc',
        ?int $limit = null,
    ): array {
        return $dbConnection
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

    /**
     * Create relation object in db
     *
     * @param PostgresDb $dbConnection
     * @param string     $rootResourceId
     * @param string     $relatedResourceId
     *
     * @return array
     */
    public function create(
        PostgresDb $dbConnection,
        string $rootResourceId,
        string $relatedResourceId
    ): array {
        return $dbConnection
                    ->insert(
                        $this->getTableName(),
                        [
                            $this->rootResourceIdField    => $rootResourceId,
                            $this->relatedResourceIdField => $relatedResourceId,
                        ]
                    )
            ;
    }

    /**
     * Remove relation object from db
     *
     * @param PostgresDb $dbConnection
     * @param string     $rootResourceId
     * @param string     $relatedResourceId
     *
     * @return int
     */
    public function delete(
        PostgresDb $dbConnection,
        string $rootResourceId,
        string $relatedResourceId
    ): int {
        return $dbConnection
                    ->delete(
                        $this->getTableName(),
                        [
                            "$this->rootResourceIdField="    => $rootResourceId,
                            "$this->relatedResourceIdField=" => $relatedResourceId,
                        ]
                    )
            ;
    }

    /**
     * Check if relation exists in db
     *
     * @param PostgresDb $dbConnection
     * @param string     $rootResourceId
     * @param string     $relatedResourceId
     *
     * @return bool
     */
    public function exists(
        PostgresDb $dbConnection,
        string $rootResourceId,
        string $relatedResourceId
    ): bool {
        return $dbConnection
                    ->exists(
                        $this->getTableName(),
                        [
                            "$this->rootResourceIdField="    => $rootResourceId,
                            "$this->relatedResourceIdField=" => $relatedResourceId,
                        ]
                    )
            ;
    }
}
