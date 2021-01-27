<?php
declare(strict_types=1);

namespace framework\Relation;

use config\DatabaseConfig;
use framework\Database\MedooHandler;
use framework\Resource\Resource;
use Medoo\Medoo;

abstract class Relation
{
    protected string $databaseName;

    protected string $rootTableName;

    protected string $relatedTableName;

    protected Medoo $database;

    public function __construct(string $rootResource, $relatedResource)
    {
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