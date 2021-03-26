<?php

declare(strict_types=1);

namespace papi\Relation;

/**
 * Resource object field type, Contains info on database schema storage of a given relation type
 */
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

    /**
     * Get SQL definitions of columns making up the relation in format ['name' => 'field_type']
     *
     * @return array
     */
    abstract public function getColumnDefinitions(): array;

    /**
     * Get SQL definition of db foreign keys included in relation
     *
     * @return array
     */
    abstract public function getForeignKeyDefinition(): array;

    /**
     * Get SQL definition of db indexes included in relation
     *
     * @return array
     */
    abstract public function getIndexDefinition(): array;

    /**
     * Get name of the db table containing relation ids
     *
     * @return string
     */
    abstract public function getTableName(): string;
}
