<?php
declare(strict_types=1);

namespace papi\CLI\Commands\Database;

use papi\CLI\Command;
use papi\CLI\ConsoleOutput;
use papi\Migrations\Schema\SchemaManager;

class DatabaseDrop implements Command
{
    public function getCommand(): string
    {
        return 'db:drop';
    }

    public function execute(): void
    {
        (new SchemaManager())->dropDb();
        ConsoleOutput::success("Database dropped!");
    }
}
