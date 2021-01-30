<?php
declare(strict_types=1);

namespace papi\Relation;

class ManyToOne extends Relation
{
    public function getRelationFieldName(): string
    {
        return $this->relatedTableName."_id";
    }

    protected function getColumnDefinition(): string
    {
        return "ALTER TABLE $this->rootTableName ADD ".$this->getRelationFieldName()." INT";
    }

    protected function getForeignKeyDefinition(): array
    {
        return [
            "ALTER TABLE $this->rootTableName ADD FOREIGN KEY ($this->relatedTableName"
            ."_id) REFERENCES $this->relatedTableName(id) $this->onDelete $this->onUpdate",
        ];
    }

    protected function getIndexDefinition(): array
    {
        return [
            'CREATE INDEX FK_'.$this->rootTableName.'_'
            ."$this->relatedTableName ON $this->rootTableName($this->relatedTableName".'_id)',
        ];
    }
}