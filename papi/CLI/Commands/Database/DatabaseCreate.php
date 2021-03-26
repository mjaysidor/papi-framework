<?php

declare(strict_types=1);

namespace papi\CLI\Commands\Database;

use papi\CLI\Command;
use papi\CLI\ConsoleOutput;
use papi\Migrations\Schema\SchemaManager;

/**
 * Creates database specified in DatabaseConfig class
 */
class DatabaseCreate implements Command
{
    public function getCommand(): string
    {
        return 'db:create';
    }

    public function getDescription(): string
    {
        return 'Creates the database specified in DatabaseConfig class';
    }

    public function execute(): void
    {
        (new SchemaManager())->createDb();
        ConsoleOutput::success("Database created!");
    }
}
