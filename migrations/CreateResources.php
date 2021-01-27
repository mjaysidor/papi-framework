<?php
declare(strict_types=1);

namespace migrations;

use config\Resources;
use framework\Migrations\Migration;

class CreateResources extends Migration
{
    public function migrate(): void
    {
        foreach (Resources::getItems() as $resource) {
            $resourceObject = new $resource();
            $this->handler->create(
                $resourceObject->getTableName(),
                $resourceObject->getMigrationColumns(),
            );
        }

        foreach (Resources::getItems() as $resource) {
            $resourceObject = new $resource();
            foreach ($resourceObject->getRelations() as $relation) {
                $relation->createRelation();
            }
        }
    }
}