<?php
declare(strict_types=1);

namespace papi\Relation;

class OneToOne extends Relation
{
    public function getRelationFieldName(): string
    {
        return $this->relatedTableName."_id";
    }

    public function getMappingSchema(): array
    {
        return [$this->getRelationFieldName() => "INT"];
    }

    public function getForeignKeyDefinition(): array
    {
        return [
            $this->rootTableName =>
                [
                    $this->relatedTableName."_id)" =>
                        "REFERENCES $this->relatedTableName(id) $this->onDelete $this->onUpdate",
                ],
        ];
    }

    public function getIndexDefinition(): array
    {
        return [
            'UNIQUE INDEX FKU_'.$this->rootTableName.'_'.$this->relatedTableName
            ."ON $this->rootTableName($this->relatedTableName".'_id)',
        ];
    }

    public function getTableName(): string
    {
        return $this->rootTableName;
    }
}