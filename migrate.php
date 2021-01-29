<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use config\Migrations;
use framework\CLI\ConsoleOutput;

foreach (Migrations::getItems() as $migration) {
    (new $migration)->execute();
}
ConsoleOutput::success('All migrations executed!');