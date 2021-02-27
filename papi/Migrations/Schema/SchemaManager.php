<?php
declare(strict_types=1);

namespace papi\Migrations\Schema;

use config\DatabaseConfig;
use papi\CLI\ConsoleOutput;
use papi\Config\ProjectStructure;

class SchemaManager
{
    public const MIGRATION_COLUMN_NAME = 'migrations_executed';

    public static function createDb(?string $customName = null): void
    {
        if ($customName) {
            $name = $customName;
        } else {
            $name = DatabaseConfig::getName();
        }
        $user = DatabaseConfig::getUsername();
        $password = DatabaseConfig::getPassword();

        $host = DatabaseConfig::getServer();
        $connection = pg_connect("host = $host dbname = postgres user = $user password = $password");

        $result = pg_query($connection, "create database $name owner $user;");

        if (! $result) {
            ConsoleOutput::errorDie(pg_last_error($connection));
        }

        $connection = pg_connect("host = $host dbname = $name user = $user password = $password");

        $migrationPathLength = strlen(ProjectStructure::getMigrationsPath()) + 35;
        pg_query(
            $connection,
            "create table ".self::MIGRATION_COLUMN_NAME
            ." (id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY, migration VARCHAR($migrationPathLength), current_state TEXT)"
        );
    }
}