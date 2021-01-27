<?php
declare(strict_types=1);

namespace framework\Relation;

class ManyToMany extends Relation
{
    public string $rootResourceIdField;

    public string $relatedResourceIdField;

    public function __construct($rootResource, $relatedResource)
    {
        parent::__construct($rootResource, $relatedResource);
        $this->rootResourceIdField = $this->rootTableName.'_id';
        $this->relatedResourceIdField = $this->relatedTableName.'_id';
    }

    public function getRelationFieldName(): ?string
    {
        return null;
    }

    public function getTableNameWithoutDatabase(): string
    {
        return $this->rootTableName.'_'.$this->relatedTableName;
    }

    public function getTableName(): string
    {
        return $this->databaseName.$this->rootTableName.'_'.$this->relatedTableName;
    }

    protected function getColumnDefinition(): string
    {
        return "CREATE TABLE ".$this->getTableName()
               ."($this->rootResourceIdField INT NOT NULL, $this->relatedResourceIdField INT NOT NULL)";
    }

    protected function getForeignKeyDefinition(): string
    {
        return "ALTER TABLE ".$this->getTableName()
               ." ADD FOREIGN KEY ($this->rootResourceIdField) REFERENCES $this->databaseName$this->rootTableName(id); ALTER TABLE "
               .$this->getTableName()
               ." ADD FOREIGN KEY ($this->relatedResourceIdField) REFERENCES $this->databaseName$this->relatedTableName(id);";
    }

    protected function getIndexDefinition(): string
    {
        return "CREATE INDEX FK_".$this->rootTableName.'_'.$this->relatedTableName.'_'."$this->rootTableName ON "
               .$this->getTableName()."($this->rootResourceIdField);"
               .
               "CREATE INDEX FK_".$this->rootTableName.'_'.$this->relatedTableName.'_'."$this->relatedTableName ON "
               .$this->getTableName()."($this->relatedResourceIdField);";
    }
}