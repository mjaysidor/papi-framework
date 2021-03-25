<?php

declare(strict_types=1);

namespace papi\Migrations\Schema\Mapping;

use papi\Config\ProjectStructure;
use papi\Relation\Relation;
use papi\Resource\Field\Field;
use papi\Utils\ClassGetter;

/**
 * Contains database structure (tables, columns, foreign keys, indexes, etc.)
 * based on current defined resources in application code
 */
class CodeMapping extends Mapping
{
    private array $resources;

    public function __construct()
    {
        $this->resources = ClassGetter::getClasses(ProjectStructure::getResourcesPath());
        parent::__construct();
    }

    protected function init(): void
    {
        foreach ($this->resources as $resource) {
            $resourceObject = new $resource();
            $this->addTable($resourceObject->getTableName());

            foreach ($resourceObject->getFields() as $name => $field) {
                if ($field instanceof Field) {
                    $this->addColumn(
                        $resourceObject->getTableName(),
                        $name,
                        $field->getProperties()
                    );
                }

                if ($field instanceof Relation) {
                    foreach ($field->getColumnDefinitions() as $column => $properties) {
                        $this->addColumn(
                            $field->getTableName(),
                            $column,
                            $properties
                        );
                    }
                    foreach ($field->getForeignKeyDefinition() as $tableName => $foreignKeys) {
                        $this->addFK($tableName, $foreignKeys);
                    }
                    foreach ($field->getIndexDefinition() as $definition) {
                        $this->addIndex($definition);
                    }
                }
            }
        }
    }

    /**
     * Adds table to data
     *
     * @param string $table
     */
    private function addTable(string $table): void
    {
        $this->tables[$table] = [];
    }

    /**
     * Adds column to data
     *
     * @param string $table
     * @param string $column
     * @param string $properties
     */
    private function addColumn(
        string $table,
        string $column,
        string $properties
    ): void {
        $this->tables[$table][$column] = $properties;
    }

    /**
     * Adds foreign key to data
     *
     * @param string $table
     * @param array  $foreignKeys
     */
    private function addFK(
        string $table,
        array $foreignKeys
    ): void {
        $this->FKs[$table] = $foreignKeys;
    }

    /**
     * Adds index to data
     *
     * @param string $definition
     */
    private function addIndex(string $definition): void
    {
        $this->indexes[] = $definition;
    }
}
