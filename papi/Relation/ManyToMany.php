<?php
declare(strict_types=1);

namespace papi\Relation;

class ManyToMany extends Relation
{
    public string $rootResourceIdField;

    public string $relatedResourceIdField;

    public function __construct(
        $rootResource,
        $relatedResource,
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

    protected function getColumnDefinition(): string
    {
        return "CREATE TABLE ".$this->getTableName()
               ."(id SERIAL NOT NULL PRIMARY KEY, $this->rootResourceIdField INT NOT NULL, $this->relatedResourceIdField INT NOT NULL)";
    }

    protected function getForeignKeyDefinition(): array
    {
        return [
            "ALTER TABLE ".$this->getTableName()
            ." ADD FOREIGN KEY ($this->rootResourceIdField) REFERENCES $this->rootTableName(id) $this->onDelete $this->onUpdate;",
            "ALTER TABLE ".$this->getTableName()
            ." ADD FOREIGN KEY ($this->relatedResourceIdField) REFERENCES $this->relatedTableName(id) $this->onDelete $this->onUpdate;",
        ];
    }

    protected function getIndexDefinition(): array
    {
        return [
            "CREATE INDEX FK_".$this->rootTableName.'_'.$this->relatedTableName.'_'."$this->rootTableName ON "
            .$this->getTableName()."($this->rootResourceIdField);"
            ,
            "CREATE INDEX FK_".$this->rootTableName.'_'.$this->relatedTableName.'_'."$this->relatedTableName ON "
            .$this->getTableName()."($this->relatedResourceIdField);",
        ];
    }
}