<?php
declare(strict_types=1);

namespace papi\Utils;

class ProjectRootDirGetter
{
    public static function getDir(): string
    {
        return str_ireplace('/papi/Utils', '', __DIR__);
    }
}