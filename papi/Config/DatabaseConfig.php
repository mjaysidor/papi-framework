<?php

declare(strict_types=1);

namespace papi\Config;

/**
 * Database connection config
 */
interface DatabaseConfig
{
    /**
     * Get database name
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Get database server (host)
     *
     * @return string
     */
    public static function getServer(): string;

    /**
     * Is database hosted on the same server as the application (localhost)?
     * Setting to true improves performance of postgres connections
     *
     * @return bool
     */
    public static function isLocal(): bool;

    /**
     * Get database username
     *
     * @return string
     */
    public static function getUsername(): string;

    /**
     * Get database user password
     *
     * @return string
     */
    public static function getPassword(): string;
}
