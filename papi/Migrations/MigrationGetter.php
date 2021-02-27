<?php
declare(strict_types=1);

namespace papi\Migrations;

use papi\Database\PostgresDb;
use papi\Utils\ClassGetter;

class MigrationGetter
{
    public static function getAll(): array
    {
        return ClassGetter::getClasses('migrations');
    }

    public static function getUnexecuted(): array
    {
        $migrations = self::getAll();
        $executedMigrations = (new PostgresDb())->select('migrations_executed', ['migration']);

        $executedMigrations = array_map(
            static function ($element) {
                return $element['migration'];
            },
            $executedMigrations
        );
        $unexecutedMigrations = array_diff($migrations, $executedMigrations);

        return array_map(
            static function ($element) {
                return new $element;
            },
            $unexecutedMigrations
        );
    }
}