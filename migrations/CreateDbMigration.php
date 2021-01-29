<?php
declare(strict_types=1);

namespace migrations;

use config\DatabaseConfig;
use PDO;
use PDOException;

class CreateDbMigration
{
    public function migrate(): void
    {
        $host = DatabaseConfig::getServer();
        $user = DatabaseConfig::getUsername();
        $password = DatabaseConfig::getPassword();
        $name = DatabaseConfig::getName();

        try {
            $dbh = new PDO("mysql:host=$host", $user, $password);
            $dbh->exec("CREATE DATABASE IF NOT EXISTS `$name`;");
        } catch (PDOException $e) {
            die("DB ERROR: ".$e->getMessage().' AT: '.$e->getTraceAsString());
        }
    }
}