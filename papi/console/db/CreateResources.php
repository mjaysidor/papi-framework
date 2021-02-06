<?php
declare(strict_types=1);

namespace papi\console\db;

use config\Resources;
use papi\CLI\ConsoleOutput;
use papi\Migrations\Migration;
use papi\Relation\Relation;
use papi\Resource\Field\Field;

class CreateResources extends Migration
{
    public function migrate(): void
    {
        foreach (Resources::getItems() as $resource) {
            $resourceObject = new $resource();
            $columns = [];
            foreach ($resourceObject->getFields() as $name => $field) {
                if ($field instanceof Field) {
                    $columns[$name] = $field->getProperties();
                }
            }

            $result = $this->handler->createTable(
                $resourceObject->getTableName(),
                $columns
            );

            if (! $result) {
                ConsoleOutput::errorDie($this->handler->getError());
            }
        }
        ConsoleOutput::output('Resources created');

        foreach (Resources::getItems() as $resource) {
            $resourceObject = new $resource();
            foreach ($resourceObject->getFields() as $field) {
                if ($field instanceof Relation) {
                    $result = $field->createRelation();
                    if (! $result) {
                        ConsoleOutput::errorDie($this->handler->getError());
                    }
                }
            }
        }
        ConsoleOutput::output('Relations created');
    }
}