<?php
declare(strict_types=1);

namespace config;

use JetBrains\PhpStorm\Pure;
use papi\Config\BootstrapConfig;

class Migrations implements BootstrapConfig
{
    #[Pure] public static function getItems(): array
    {
        return [
        ];
    }
}