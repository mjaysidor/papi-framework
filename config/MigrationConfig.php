<?php
declare(strict_types=1);

namespace config;

use papi\Config\AbsolutePathConfig;

class MigrationConfig implements AbsolutePathConfig
{
    public static function getAbsolutePath(): string
    {
        return '/var/www/projekty/mark/ihmj/migrations';
    }
}