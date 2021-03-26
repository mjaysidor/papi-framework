<?php

declare(strict_types=1);

namespace papi\CLI\Commands\Database;

use papi\CLI\Command;
use papi\CLI\ConsoleOutput;
use papi\Migrations\Schema\SchemaManager;

/**
 * Drops the database specified in DatabaseConfig class
 */
class DatabaseDrop implements Command
{
    public function getCommand(): string
    {
        return 'db:drop';
    }

    public function getDescription(): string
    {
        return 'Drops the database specified in DatabaseConfig class';
    }

    public function execute(): void
    {
        (new SchemaManager())->dropDb();
        ConsoleOutput::success("Database dropped!");
    }
}
