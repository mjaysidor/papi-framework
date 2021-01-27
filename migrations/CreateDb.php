<?php
declare(strict_types=1);

namespace migrations;

use config\DatabaseConfig;
use framework\Migrations\Migration;
use PDO;
use PDOException;

class CreateDb extends Migration
{
    public function migrate(): void
    {
        $host = DatabaseConfig::getServer();
        $user = DatabaseConfig::getUsername();
        $password = DatabaseConfig::getPassword();
        $name = DatabaseConfig::getName();

        try {
            $dbh = new PDO("mysql:host=$host", $user, $password);
            $dbh->exec(
                "CREATE DATABASE IF NOT EXISTS `$name`;
                GRANT ALL ON `$name`.* TO '$user'@'$host';
                FLUSH PRIVILEGES;"
            )
            or die(print_r($dbh->errorInfo(), true));

        } catch (PDOException $e) {
            die("DB ERROR: ".$e->getMessage());
        }
    }
}