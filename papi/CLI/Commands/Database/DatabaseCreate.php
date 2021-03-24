<?php
declare(strict_types=1);

namespace papi\CLI\Commands\Database;

use papi\CLI\Command;
use papi\CLI\ConsoleOutput;
use papi\Migrations\Schema\SchemaManager;

class DatabaseCreate implements Command
{
    public function getCommand(): string
    {
        return 'db:create';
    }

    public function execute(): void
    {
        (new SchemaManager())->createDb();
        ConsoleOutput::success("Database created!");
    }
}
