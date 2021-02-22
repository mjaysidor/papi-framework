<?php
declare(strict_types=1);

namespace papi\Migrations;

use config\MigrationConfig;
use papi\Database\PostgresDb;

class MigrationGetter
{
    public static function getAll(): array
    {
        $migrations = [];
        foreach (glob(MigrationConfig::getAbsolutePath().'/*.php') as $file) {
            $class = '\\migrations\\'.basename($file, '.php');
            $migrations[] = new $class();
        }

        return $migrations;
    }

    public static function getUnexecuted(): array
    {
        $unexecutedMigrations = [];
        $db = new PostgresDb();
        foreach (self::getAll() as $migration) {
            foreach ($db->select('migrations_executed') as $executedMigration) {
                if (str_contains($executedMigration['migration'], get_class($migration))) {
                    continue 2;
                }
            }
            $unexecutedMigrations[] = $migration;
        }

        return $unexecutedMigrations;
    }
}