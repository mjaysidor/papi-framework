<?php

declare(strict_types=1);

namespace papi\Config;

interface AuthConfig
{
    /**
     * Return JWT secret
     */
    public static function getSecret(): string;
}
