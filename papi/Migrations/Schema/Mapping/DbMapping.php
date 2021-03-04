<?php
declare(strict_types=1);

namespace papi\Migrations\Schema\Mapping;

use papi\Database\PostgresDb;
use papi\Migrations\Schema\SchemaManager;

class DbMapping extends Mapping
{
    protected function init(): void
    {
        $lastMigration = (new PostgresDb())->select(
                SchemaManager::MIGRATION_COLUMN_NAME,
                ['current_state'],
                null,
                'id',
                'desc',
                1
            )[0]['current_state'] ?? [];

        if (! empty($lastMigration)) {
            $lastMigration = json_decode(
                $lastMigration,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        }

        $this->fromArray($lastMigration);
    }
}