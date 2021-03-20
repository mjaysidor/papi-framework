<?php

declare(strict_types=1);

namespace papi\Config;

interface DatabaseConfig
{
    public static function getName(): string;

    public static function getServer(): string;

    public static function isLocal(): bool;

    public static function getUsername(): string;

    public static function getPassword(): string;
}
