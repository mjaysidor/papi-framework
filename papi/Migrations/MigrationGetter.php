<?php
declare(strict_types=1);

namespace papi\Migrations;

use config\MigrationConfig;
use papi\Database\PostgresDb;
use papi\Utils\ProjectRootDirGetter;

class MigrationGetter
{
    public static function getAll(): array
    {
        $root = ProjectRootDirGetter::getDir();

        return array_map(
            static function ($filePath) use ($root) {
                return str_replace([$root, '/', '.php'], ['', '\\', ''], $filePath);
            },
            glob(MigrationConfig::getAbsolutePath().'/*.php')
        );
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