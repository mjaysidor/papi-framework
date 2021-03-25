<?php

declare(strict_types=1);

namespace papi\Resource\Field;

/**
 * Postgresql "double precision"" column type
 */
class Double extends Field
{
    protected function getDefaultProperties(): string
    {
        return "double precision";
    }

    public function getPHPTypeName(): string
    {
        return 'double';
    }
}
