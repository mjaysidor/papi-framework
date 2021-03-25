<?php
declare(strict_types=1);

namespace papi\CLI\Commands\Migrations;

use papi\CLI\Command;
use papi\CLI\ConsoleOutput;
use papi\Migrations\MigrationManager;

/**
 * Executes created migrations
 */
class MigrationsExecute implements Command
{
    public function getCommand(): string
    {
        return 'migration:execute';
    }

    public function getDescription(): string
    {
        return 'Executes created migrations';
    }

    public function execute(): void
    {
        $executionCount = MigrationManager::execute();

        if ($executionCount) {
            ConsoleOutput::success("Migrations executed: $executionCount");
        } else {
            ConsoleOutput::info('No new migrations to execute.');
        }
    }
}
