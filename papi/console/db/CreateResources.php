<?php
declare(strict_types=1);

namespace papi\console\db;

use config\Resources;
use papi\CLI\ConsoleOutput;
use papi\Migrations\Migration;

class CreateResources extends Migration
{
    public function migrate(): void
    {
        foreach (Resources::getItems() as $resource) {
            $resourceObject = new $resource();
            try {
                $this->handler->create(
                    $resourceObject->getTableName(),
                    $resourceObject->getMigrationColumns(),
                );
            } catch (\Exception $exception) {
                ConsoleOutput::errorDie($exception->getMessage());
            }
        }
        ConsoleOutput::output('Resources created');

        foreach (Resources::getItems() as $resource) {
            $resourceObject = new $resource();
            foreach ($resourceObject->getRelations() as $relation) {
                try {
                    $relation->createRelation();
                } catch (\Exception $exception) {
                    ConsoleOutput::errorDie($exception->getMessage());
                }
            }
        }
        ConsoleOutput::output('Relations created');
    }
}