<?php
declare(strict_types=1);

namespace papi\Config;

class ProjectStructure
{
    public static function getControllersPath(): string
    {
        return 'src/Controller/';
    }
    public static function getControllersNamespace(): string
    {
        return 'App\Controller';
    }

    public static function getResourcesPath(): string
    {
        return 'src/Resource/';
    }

    public static function getResourcesNamespace(): string
    {
        return 'App\Resource';
    }
}