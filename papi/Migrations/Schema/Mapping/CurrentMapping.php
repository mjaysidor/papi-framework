<?php
declare(strict_types=1);

namespace papi\Migrations\Schema\Mapping;

use papi\Config\ProjectStructure;
use papi\Relation\Relation;
use papi\Resource\Field\Field;
use papi\Utils\ClassGetter;

class CurrentMapping extends Mapping
{
    protected function init(): void
    {
        foreach (ClassGetter::getClasses(ProjectStructure::getResourcesPath()) as $resource) {
            $resourceObject = new $resource();
            $this->tables[$resourceObject->getTableName()] = [];
            foreach ($resourceObject->getFields() as $name => $field) {
                if ($field instanceof Field) {
                    $this->tables[$resourceObject->getTableName()][$name] = $field->getProperties();
                }
                if ($field instanceof Relation) {
                    foreach ($field->getMappingSchema() as $key => $item) {
                        $this->tables[$field->getTableName()][$key] = $item;
                    }
                    foreach ($field->getForeignKeyDefinition() as $table => $foreignKeys) {
                        $this->FKs[$table] = $foreignKeys;
                    }
                    foreach ($field->getIndexDefinition() as $definition) {
                        $this->indexes[] = $definition;
                    }
                }
            }
        }
    }
}