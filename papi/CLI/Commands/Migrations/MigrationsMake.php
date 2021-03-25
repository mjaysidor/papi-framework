<?php
declare(strict_types=1);

namespace papi\CLI\Commands\Migrations;

use papi\CLI\Command;
use papi\CLI\ConsoleOutput;
use papi\Migrations\MigrationManager;

/**
 * Generates migrations based on differences between code (PHP Resource objects) and current database schema
 */
class MigrationsMake implements Command
{
    public function getCommand(): string
    {
        return 'migration:make';
    }

    public function getDescription(): string
    {
        return 'Generates migrations based on differences '
               .'between code (PHP Resource objects) and current database schema';
    }

    public function execute(): void
    {
        MigrationManager::make();
        ConsoleOutput::success('Migration created!');
    }
}
