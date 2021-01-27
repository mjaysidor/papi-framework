<?php
declare(strict_types=1);

namespace config;

use PDO;

class DatabaseConfig
{
    public static function getConfig(): array
    {
        return [
            'database_type' => 'mysql',
            'database_name' => 'ihmj',
            'server'        => '127.0.0.1',
            'username'      => 'mjsidor',
            'password'      => '!Xplod3r',
            'option' => [
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            ],
        ];
    }

    public static function getType(): string
    {
        return self::getConfig()['database_type'];
    }

    public static function getName(): string
    {
        return self::getConfig()['database_name'];
    }

    public static function getServer(): string
    {
        return self::getConfig()['server'];
    }

    public static function getUsername(): string
    {
        return self::getConfig()['username'];
    }

    public static function getPassword(): string
    {
        return self::getConfig()['password'];
    }
}