<?php
declare(strict_types=1);

namespace papi\Relation;

use config\DatabaseConfig;
use Medoo\Medoo;
use papi\Database\MedooHandler;

abstract class Relation
{
    public const ON_UPDATE_RESTRICT = 'ON UPDATE RESTRICT';
    public const ON_UPDATE_CASCADE  = 'ON UPDATE CASCADE';
    public const ON_UPDATE_SET_NULL = 'ON UPDATE SET NULL';
    public const ON_DELETE_RESTRICT = 'ON DELETE RESTRICT';
    public const ON_DELETE_CASCADE  = 'ON DELETE CASCADE';
    public const ON_DELETE_SET_NULL = 'ON DELETE SET NULL';

    protected string $databaseName;

    protected string $rootTableName;

    protected string $relatedTableName;

    protected string $onUpdate;

    protected string $onDelete;

    protected Medoo $database;

    public function __construct(
        string $rootResource,
        $relatedResource,
        string $onUpdate = self::ON_UPDATE_CASCADE,
        string $onDelete = self::ON_DELETE_CASCADE
    ) {
        $this->onUpdate = $onUpdate;
        $this->onDelete = $onDelete;
        $this->databaseName = DatabaseConfig::getName().'.';
        $this->rootTableName = (new $rootResource)->getTableName();
        $this->relatedTableName = (new $relatedResource)->getTableName();
        $this->database = MedooHandler::getDbHandler();
    }

    public function createRelation(): void
    {
        $this->database->exec($this->getColumnDefinition());
        $this->database->exec($this->getForeignKeyDefinition());
        $this->database->exec($this->getIndexDefinition());
    }

    abstract public function getRelationFieldName(): ?string;

    abstract protected function getColumnDefinition(): string;

    abstract protected function getForeignKeyDefinition(): string;

    abstract protected function getIndexDefinition(): string;
}