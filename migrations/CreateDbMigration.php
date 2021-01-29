<?php
declare(strict_types=1);

namespace migrations;

use config\DatabaseConfig;
use framework\CLI\ConsoleOutput;
use PDO;
use PDOException;

class CreateDbMigration
{
    public function execute(): void
    {
        $host = DatabaseConfig::getServer();
        $user = DatabaseConfig::getUsername();
        $password = DatabaseConfig::getPassword();
        $name = DatabaseConfig::getName();

        try {
            $dbh = new PDO("mysql:host=$host", $user, $password);
            $dbh->exec("CREATE DATABASE IF NOT EXISTS `$name`;");
        } catch (PDOException $e) {
            ConsoleOutput::error("DB ERROR: ".$e->getMessage());
            die();
        }
        ConsoleOutput::output('Database created');
    }
}