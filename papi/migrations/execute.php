<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use papi\CLI\ConsoleOutput;
use papi\Database\PostgresDb;
use papi\Migrations\MigrationGetter;
use papi\Migrations\Schema\SchemaDiffGenerator;

$executionCount = 0;
$db = new PostgresDb();
$migrationsExecuted = [];

foreach (MigrationGetter::getUnexecuted() as $migration) {
    foreach ($migration->getSQL() as $sql) {
        $result = $db->query($sql);
        if (! $result) {
            ConsoleOutput::errorDie($db->getError());
        }
    }
    if ($db->getError() === null) {
        $db->insert(
            'migrations_executed',
            [
                'migration'     =>  '\\'.get_class($migration),
                'current_state' => json_encode($migration->getMapping(), JSON_THROW_ON_ERROR),
            ]
        );
        $executionCount++;
    }
}

if ($executionCount) {
    ConsoleOutput::success("Migrations executed: $executionCount");
} else {
    ConsoleOutput::info('No new migrations to execute.');
}
