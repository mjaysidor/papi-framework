<?php

declare(strict_types=1);

namespace papi\Migrations;

use papi\CLI\ConsoleOutput;
use papi\Config\ProjectStructure;
use papi\Database\PostgresDb;
use papi\Migrations\Schema\SchemaManager;
use papi\Utils\ClassGetter;
use papi\Utils\PHPClassFileWriter;

/**
 * Makes, gets and executes migrations
 */
class MigrationManager
{
    /**
     * Make migration to update db schema to match current resource objects mapping
     */
    public static function make(): void
    {
        if (! empty(self::getUnexecuted())) {
            ConsoleOutput::info(
                'There are unexecuted migrations. Either delete them, or execute by php papi/migrations/execute'
            );
            die();
        }

        $queryBuilder = (new MigrationQueryBuilder());
        if (empty($sql = $queryBuilder->getSqlStatements())) {
            ConsoleOutput::info('Schema is up to date');
            die();
        }
        $className = "Migration_".(new \DateTime())->format('Y_m_d_h_i_s');
        $writer = new PHPClassFileWriter(
            $className,
            ProjectStructure::getMigrationsNamespace(),
            ProjectStructure::getMigrationsPath(),
            implements: 'Migration'
        );
        $writer->addImport(Migration::class);
        $writer->addFunction(
            'public',
            'array',
            'getSQL',
            'return '.var_export($sql, true).';'
        );
        $writer->addFunction(
            'public',
            'array',
            'getMapping',
            'return '.var_export($queryBuilder->getCodeMappingArray(), true).';'
        );
        $writer->write();
    }

    /**
     * Executes unexecuted migrations
     *
     * @return int
     */
    public static function execute(): int
    {
        $executionCount = 0;
        $db = new PostgresDb();

        foreach (self::getUnexecuted() as $migration) {
            foreach ($migration->getSQL() as $sql) {
                $db->query($sql);
            }
            try {
                $db->insert(
                    SchemaManager::MIGRATION_COLUMN_NAME,
                    [
                        'migration'     => get_class($migration),
                        'current_state' => json_encode($migration->getMapping(), JSON_THROW_ON_ERROR),
                    ]
                );
            } catch (\JsonException $exception) {
                ConsoleOutput::errorDie('ERROR: '.$exception->getMessage());
            }
            $executionCount++;
        }

        return $executionCount;
    }

    /**
     * Returns unexecuted migrations
     *
     * @return array
     */
    public static function getUnexecuted(): array
    {
        $migrations = ClassGetter::getClasses(ProjectStructure::getMigrationsPath());

        $executedMigrations = array_map(
            static function ($element) {
                return $element['migration'];
            },
            (new PostgresDb())->select(SchemaManager::MIGRATION_COLUMN_NAME, ['migration'])
        );

        return array_map(
            static function ($element) {
                return new $element();
            },
            array_diff($migrations, $executedMigrations)
        );
    }
}
