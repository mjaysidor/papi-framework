<?php
declare(strict_types=1);

namespace papi\Config;

abstract class DatabaseConfig
{
    abstract public static function getConfig(): array;

    public static function getType(): string
    {
        return 'pgsql';
    }

    abstract public static function getName(): string;

    abstract public static function getServer(): string;

    abstract public static function getUsername(): string;

    abstract public static function getPassword(): string;

    abstract public static function getOptions(): array;
}