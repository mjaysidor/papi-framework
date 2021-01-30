<?php
declare(strict_types=1);

namespace papi\Relation;

class OneToOne extends Relation
{
    public function getRelationFieldName(): string
    {
        return $this->relatedTableName."_id";
    }

    protected function getColumnDefinition(): string
    {
        return "ALTER TABLE $this->databaseName"."$this->rootTableName ADD ".$this->getRelationFieldName()." INT";
    }

    protected function getForeignKeyDefinition(): string
    {
        return "ALTER TABLE $this->databaseName"."$this->rootTableName ADD FOREIGN KEY ($this->relatedTableName"
               ."_id) REFERENCES $this->onDelete $this->onUpdate".$this->databaseName.$this->relatedTableName.'(id)';
    }

    protected function getIndexDefinition(): string
    {
        return 'CREATE UNIQUE INDEX FKU_'.$this->rootTableName.'_'."$this->relatedTableName ON $this->databaseName"
               .$this->rootTableName."($this->relatedTableName".'_id)';
    }
}