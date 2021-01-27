<?php
declare(strict_types=1);

namespace config;

interface BootstrapConfig
{
    public static function getItems(): array;
}