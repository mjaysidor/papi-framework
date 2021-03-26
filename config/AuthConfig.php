<?php

declare(strict_types=1);

namespace config;

class AuthConfig implements \papi\Config\AuthConfig
{
    public static function getSecret(): string
    {
        return '';
    }
}
