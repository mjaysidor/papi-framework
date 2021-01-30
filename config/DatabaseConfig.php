<?php
declare(strict_types=1);

namespace config;

use PDO;

class DatabaseConfig extends \papi\Config\DatabaseConfig
{
    public static function getConfig(): array
    {
        return [
            'database_type' => self::getType(),
            'database_name' => self::getName(),
            'server'        => self::getServer(),
            'username'      => self::getUsername(),
            'password'      => self::getPassword(),
            'option'        => self::getOptions(),
        ];
    }

    public static function getName(): string
    {
        return 'ihmj';
    }

    public static function getServer(): string
    {
        return '127.0.0.1';
    }

    public static function getUsername(): string
    {
        return 'mjsidor';
    }

    public static function getPassword(): string
    {
        return '!Xplod3r';
    }

    public static function getOptions(): array
    {
        return [
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
        ];
    }
}