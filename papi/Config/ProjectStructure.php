<?php
declare(strict_types=1);

namespace papi\Config;

class ProjectStructure implements ProjectStructureConfig
{
    public static function getControllersPath(): string
    {
        return 'src/Controller';
    }

    public static function getControllersNamespace(): string
    {
        return 'App\Controller';
    }

    public static function getResourcesPath(): string
    {
        return 'src/Resource';
    }

    public static function getResourcesNamespace(): string
    {
        return 'App\Resource';
    }

    public static function getMigrationsPath(): string
    {
        return 'migrations';
    }

    public static function getOpenApiDocPath(): string
    {
        return 'doc/open_api_endpoints.yaml';
    }

    public static function getMigrationsNamespace(): string
    {
        return 'migrations';
    }
}