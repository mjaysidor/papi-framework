<?php
declare(strict_types=1);

namespace papi\CLI\Commands\Migrations;

use papi\CLI\Command;
use papi\CLI\ConsoleOutput;
use papi\Migrations\MigrationManager;

class MigrationsMake implements Command
{
    public function getCommand(): string
    {
        return 'migration:make';
    }

    public function execute(): void
    {
        MigrationManager::make();
        ConsoleOutput::success('Migration created!');
    }
}
