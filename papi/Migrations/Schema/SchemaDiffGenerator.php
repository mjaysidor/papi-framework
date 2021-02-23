<?php
declare(strict_types=1);

namespace papi\Migrations\Schema;

use papi\Migrations\Schema\Mapping\CurrentMapping;
use papi\Migrations\Schema\Mapping\DbMapping;
use papi\Utils\ArrayDiff;

class SchemaDiffGenerator
{
    private CurrentMapping $currentMapping;

    private DbMapping $dbMapping;

    public array $tablesToCreate = [];

    public array $tablesToRemove = [];

    public array $columnsToCreate = [];

    public array $columnsToChange = [];

    public array $columnsToRemove = [];

    public array $foreignKeysToCreate = [];

    public array $foreignKeysToRemove = [];

    public array $indexesToCreate = [];

    public array $indexesToRemove = [];

    public function __construct()
    {
        $this->currentMapping = new CurrentMapping();
        $this->dbMapping = new DbMapping();
        $this->initTablesDiff();
        $this->initFKsDiff();
        $this->initIndexesDiff();
    }

    private function initTablesDiff(): void
    {
        $currentTables = $this->currentMapping->getTables();
        $dbTables = $this->dbMapping->getTables();

        // remove unchanged tables
        ArrayDiff::removeArrayCommonElements($currentTables, $dbTables);

        // get tables & columns to remove
        foreach ($dbTables as $table => $fields) {
            if (! isset($currentTables[$table])) {
                $this->tablesToRemove[] = $table;
                continue;
            }
            foreach ($fields as $field => $options) {
                if (! isset($currentTables[$table][$field])) {
                    $this->columnsToRemove[$table][] = $field;
                }
            }
        }

        // get tables & columns to create
        foreach ($currentTables as $table => $fields) {
            if (! isset($dbTables[$table])) {
                $this->tablesToCreate[$table] = $fields;
                continue;
            }
            foreach ($fields as $field => $options) {
                if (isset($dbTables[$table][$field])) {
                    $this->columnsToChange[$table][$field] = $options;
                } else {
                    $this->columnsToCreate[$table][$field] = $options;
                }
            }
        }
    }

    private function initFKsDiff(): void
    {
        $currentFKs = $this->currentMapping->getFKs();
        $dbFKs = $this->dbMapping->getFKs();
        ArrayDiff::removeArrayCommonElements($currentFKs, $dbFKs);
        $this->foreignKeysToCreate = $currentFKs;
        $this->foreignKeysToRemove = $dbFKs;
    }

    private function initIndexesDiff(): void
    {
        $this->indexesToRemove = array_diff($this->dbMapping->getIndexes(), $this->currentMapping->getIndexes());
        $this->indexesToCreate = array_diff($this->currentMapping->getIndexes(), $this->dbMapping->getIndexes());
    }

    public function getCurrentMapping(): CurrentMapping
    {
        return $this->currentMapping;
    }
}