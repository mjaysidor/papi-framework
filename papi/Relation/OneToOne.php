<?php

declare(strict_types=1);

namespace papi\Relation;

class OneToOne extends Relation
{
    /**
     * Get name of the field containing id of relation
     *
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->relatedTableName . "_id";
    }

    public function getColumnDefinitions(): array
    {
        return [$this->getColumnName() => "INT"];
    }

    public function getForeignKeyDefinition(): array
    {
        return [
            $this->rootTableName =>
                [
                    $this->relatedTableName . "_id" =>
                        "REFERENCES $this->relatedTableName(id) $this->onDelete $this->onUpdate",
                ],
        ];
    }

    public function getIndexDefinition(): array
    {
        return [
            'unique index FKU_' . $this->rootTableName . '_' . $this->relatedTableName
            . " on $this->rootTableName($this->relatedTableName" . '_id)',
        ];
    }

    public function getTableName(): string
    {
        return $this->rootTableName;
    }
}
