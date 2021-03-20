<?php

declare(strict_types=1);

namespace papi\Config;

interface ProjectStructureConfig
{
    public static function getControllersPath(): string;

    public static function getControllersNamespace(): string;

    public static function getResourcesPath(): string;

    public static function getResourcesNamespace(): string;

    public static function getMigrationsPath(): string;

    public static function getOpenApiDocPath(): string;

    public static function getMigrationsNamespace(): string;
}
