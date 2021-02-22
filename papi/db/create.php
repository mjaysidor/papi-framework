<?php
declare(strict_types=1);

use papi\CLI\ConsoleOutput;
use papi\Migrations\SchemaManager;

require 'vendor/autoload.php';

SchemaManager::createDb();
ConsoleOutput::success("Database created (pgsql)");
