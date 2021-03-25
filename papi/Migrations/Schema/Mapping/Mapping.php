<?php

declare(strict_types=1);

namespace papi\Migrations\Schema\Mapping;

/**
 * Contains database structure (tables, columns, foreign keys, indexes, etc.)
 */
abstract class Mapping
{
    protected array $tables = [];

    protected array $FKs = [];

    protected array $indexes = [];

    public function __construct()
    {
        $this->init();
    }

    public function getTables(): array
    {
        return $this->tables;
    }

    public function getFKs(): array
    {
        return $this->FKs;
    }

    public function getIndexes(): array
    {
        return $this->indexes;
    }

    /**
     * Returns array containing database structure information
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'tables'       => $this->tables,
            'foreign_keys' => $this->FKs,
            'indexes'      => $this->indexes,
        ];
    }

    /**
     * Initializes object from array containing database structure information
     *
     * @param array $array
     */
    protected function fromArray(array $array): void
    {
        $this->tables = $array['tables'] ?? [];
        $this->FKs = $array['foreign_keys'] ?? [];
        $this->indexes = $array['indexes'] ?? [];
    }

    abstract protected function init(): void;
}
