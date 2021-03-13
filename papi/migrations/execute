<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use papi\CLI\ConsoleOutput;
use papi\Migrations\MigrationManager;

$executionCount = MigrationManager::execute();

if ($executionCount) {
    ConsoleOutput::success("Migrations executed: $executionCount");
} else {
    ConsoleOutput::info('No new migrations to execute.');
}
