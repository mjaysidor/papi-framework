<?php
declare(strict_types=1);

namespace config;

use framework\Config\BootstrapConfig;
use JetBrains\PhpStorm\Pure;
use migrations\CreateResources;

class Migrations implements BootstrapConfig
{
    #[Pure] public static function getItems(): array
    {
        return [
//            CreateDb::class,
            CreateResources::class,
        ];
    }
}