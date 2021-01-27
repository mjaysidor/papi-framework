<?php
declare(strict_types=1);

namespace framework\Config;

interface BootstrapConfig
{
    public static function getItems(): array;
}