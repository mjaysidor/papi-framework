<?php
declare(strict_types=1);

namespace papi\Config;

interface AbsolutePathConfig
{
    public static function getAbsolutePath(): string;
}