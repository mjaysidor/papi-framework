<?php

declare(strict_types=1);

namespace papi\Resource\Field;

/**
 * Postgresql "timestamp"" column type
 */
class Timestamp extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'timestamp';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}
