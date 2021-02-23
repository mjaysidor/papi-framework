<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use config\DatabaseConfig;
use papi\CLI\ConsoleOutput;

$isLocal = DatabaseConfig::isLocal();
$name = DatabaseConfig::getName();
$user = DatabaseConfig::getUsername();
$password = DatabaseConfig::getPassword();

if ($isLocal) {
    $connection = pg_connect("dbname = postgres user = $user password = $password");
}

$host = DatabaseConfig::getServer();
$connection = pg_connect("host = $host dbname = postgres user = $user password = $password");

$result = pg_query($connection, "DROP DATABASE $name;");
if (! $result) {
    ConsoleOutput::errorDie(pg_last_error($connection));
}
ConsoleOutput::success("Database dropped (pgsql)");
