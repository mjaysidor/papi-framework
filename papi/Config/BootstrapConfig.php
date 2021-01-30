<?php
declare(strict_types=1);

namespace papi\Config;

interface BootstrapConfig
{
    public static function getItems(): array;
}