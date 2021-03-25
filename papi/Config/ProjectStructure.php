<?php

declare(strict_types=1);

namespace papi\Config;

/**
 * Defines project (directory) structure
 */
class ProjectStructure
{
    /**
     * Get directory containing config files
     *
     * @return string
     */
    public static function getConfigPath(): string
    {
        return 'config';
    }

    /**
     * Get namespace of directory containing config files
     *
     * @return string
     */
    public static function getConfigNamespace(): string
    {
        return 'config';
    }

    /**
     * Get directory containing controllers
     *
     * @return string
     */
    public static function getControllersPath(): string
    {
        return 'src/Controller';
    }

    /**
     * Get directory containing voters
     *
     * @return string
     */
    public static function getVoterPath(): string
    {
        return 'src/Voter';
    }

    /**
     * Get directory containing Many To Many Controllers
     *
     * @return string
     */
    public static function getManyToManyControllersPath(): string
    {
        return 'src/Controller/ManyToMany';
    }

    /**
     * Get namespace of directory containing controllers
     *
     * @return string
     */
    public static function getControllersNamespace(): string
    {
        return 'App\Controller';
    }

    /**
     * Get namespace of directory containing Many To Many Controllers
     *
     * @return string
     */
    public static function getManyToManyControllersNamespace(): string
    {
        return 'App\Controller\ManyToMany';
    }

    /**
     * Get directory containing resources
     *
     * @return string
     */
    public static function getResourcesPath(): string
    {
        return 'src/Resource';
    }

    /**
     * Get namespace of directory containing resources
     *
     * @return string
     */
    public static function getResourcesNamespace(): string
    {
        return 'App\Resource';
    }

    /**
     * Get directory containing migrations
     *
     * @return string
     */
    public static function getMigrationsPath(): string
    {
        return 'migrations';
    }

    /**
     * Get directory containing OpenAPI doc
     *
     * @return string
     */
    public static function getOpenApiDocPath(): string
    {
        return 'doc/open_api_endpoints.yaml';
    }

    /**
     * Get namespace of directory containing voters
     *
     * @return string
     */
    public static function getMigrationsNamespace(): string
    {
        return 'migrations';
    }
}
