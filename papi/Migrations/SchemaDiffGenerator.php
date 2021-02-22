<?php
declare(strict_types=1);

namespace papi\Migrations;

use config\Resources;
use JetBrains\PhpStorm\Pure;
use papi\Database\PostgresDb;
use papi\Relation\Relation;
use papi\Resource\Field\Field;
use papi\Utils\ArrayDiff;

class SchemaDiffGenerator
{
    public array $currentMappingTables;

    public array $dbTables;

    public array $currentMappingFKs;

    public array $dbFKs;

    public array $currentMappingIndexes;

    public array $dbIndexes;

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
        $this->initCurrentMapping();
        $this->initDbMapping();
        $this->initTablesAndColumns();
        $this->initFKs();
        $this->initIndexes();
    }

    private function initTablesAndColumns(): void
    {
        $currentTables = $this->currentMappingTables;
        $dbTables = $this->dbTables;

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

    private function initFKs(): void
    {
        $currentFKs = $this->currentMappingFKs;
        $dbFKs = $this->dbFKs;

        ArrayDiff::removeArrayCommonElements($currentFKs, $dbFKs);
        $this->foreignKeysToCreate = $currentFKs;
        $this->foreignKeysToRemove = $dbFKs;
    }

    private function initIndexes(): void
    {
        $this->indexesToRemove = array_diff($this->dbIndexes, $this->currentMappingIndexes);
        $this->indexesToCreate = array_diff($this->currentMappingIndexes, $this->dbIndexes);
    }

    private function initCurrentMapping(): void
    {
        foreach (Resources::getItems() as $resource) {
            $resourceObject = new $resource();
            $this->currentMappingTables[$resourceObject->getTableName()] = [];
            foreach ($resourceObject->getFields() as $name => $field) {
                if ($field instanceof Field) {
                    $this->currentMappingTables[$resourceObject->getTableName()][$name] = $field->getProperties();
                }
                if ($field instanceof Relation) {
                    $this->currentMappingTables[$field->getTableName()] = $field->getMappingSchema();
                }
            }
        }
        foreach (Resources::getItems() as $resource) {
            $resourceObject = new $resource();
            foreach ($resourceObject->getFields() as $field) {
                if ($field instanceof Relation) {
                    foreach ($field->getForeignKeyDefinition() as $table => $foreignKeys) {
                        $this->currentMappingFKs[$table] = $foreignKeys;
                    }
                    foreach ($field->getIndexDefinition() as $definition) {
                        $this->currentMappingIndexes[] = $definition;
                    }
                }
            }
        }
    }

    private function initDbMapping(): void
    {
        $mapping = json_decode(
            (new PostgresDb())->select(
                SchemaManager::MIGRATION_COLUMN_NAME,
                ['current_state'],
                null,
                'id',
                'desc',
                1
            )[0]['current_state'],
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $this->dbTables = $mapping['tables'] ?? [];
        $this->dbFKs = $mapping['foreign_keys'] ?? [];
        $this->dbIndexes = $mapping['indexes'] ?? [];
    }

    #[Pure] public function getCurrentSchema(): array
    {
        return [
            'tables'       => $this->currentMappingTables,
            'foreign_keys' => $this->currentMappingFKs,
            'indexes'      => $this->currentMappingIndexes,
        ];
    }
}