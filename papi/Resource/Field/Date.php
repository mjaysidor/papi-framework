<?php

declare(strict_types=1);

namespace papi\Resource\Field;

/**
 * Postgresql "date"" column type
 */
class Date extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'date';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}
