<?php

declare(strict_types=1);

namespace papi\Relation;

abstract class Relation
{
    public const ON_UPDATE_RESTRICT = 'on update restrict';
    public const ON_UPDATE_CASCADE  = 'on update cascade';
    public const ON_UPDATE_SET_NULL = 'on update set null';
    public const ON_DELETE_RESTRICT = 'on delete restrict';
    public const ON_DELETE_CASCADE  = 'on delete cascade';
    public const ON_DELETE_SET_NULL = 'on delete set null';

    protected string $rootTableName;

    protected string $relatedTableName;

    protected string $onUpdate;

    protected string $onDelete;

    public function __construct(
        string $rootResource,
        string $relatedResource,
        string $onUpdate = self::ON_UPDATE_CASCADE,
        string $onDelete = self::ON_DELETE_CASCADE
    ) {
        $this->onUpdate = $onUpdate;
        $this->onDelete = $onDelete;
        $this->rootTableName = (new $rootResource())->getTableName();
        $this->relatedTableName = (new $relatedResource())->getTableName();
    }

    abstract public function getRelationFieldName(): ?string;

    abstract public function getColumnDefinitions(): array;

    abstract public function getForeignKeyDefinition(): array;

    abstract public function getIndexDefinition(): array;

    abstract public function getTableName(): string;
}
