<?php

declare(strict_types=1);

namespace papi\Migrations\Schema;

use papi\Migrations\Schema\Mapping\CodeMapping;
use papi\Migrations\Schema\Mapping\DbMapping;
use papi\Utils\ArrayDiff;

/**
 * Class SchemaDiffGenerator
 *
 * @package papi\Migrations\Schema
 */
class SchemaDiffGenerator
{
    private CodeMapping $codeMapping;

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
        $this->codeMapping = new CodeMapping();
        $this->dbMapping = new DbMapping();
        $this->initTablesDiff();
        $this->initFKsDiff();
        $this->initIndexesDiff();
    }

    private function initTablesDiff(): void
    {
        $codeMappingTables = $this->codeMapping->getTables();
        $dbTables = $this->dbMapping->getTables();

        ArrayDiff::removeArrayCommonElements($codeMappingTables, $dbTables);

        // get tables & columns to remove
        foreach ($dbTables as $table => $fields) {
            if (! isset($codeMappingTables[$table])) {
                $this->tablesToRemove[] = $table;
                continue;
            }
            foreach ($fields as $field => $options) {
                if (! isset($codeMappingTables[$table][$field])) {
                    $this->columnsToRemove[$table][] = $field;
                }
            }
        }

        // get tables & columns to create
        foreach ($codeMappingTables as $table => $fields) {
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
        $codeFKs = $this->codeMapping->getFKs();
        $dbFKs = $this->dbMapping->getFKs();
        ArrayDiff::removeArrayCommonElements($codeFKs, $dbFKs);
        $this->foreignKeysToCreate = $codeFKs;
        $this->foreignKeysToRemove = $dbFKs;
    }

    private function initIndexesDiff(): void
    {
        $this->indexesToRemove = array_diff($this->dbMapping->getIndexes(), $this->codeMapping->getIndexes());
        $this->indexesToCreate = array_diff($this->codeMapping->getIndexes(), $this->dbMapping->getIndexes());
    }

    public function getCodeMapping(): CodeMapping
    {
        return $this->codeMapping;
    }
}
