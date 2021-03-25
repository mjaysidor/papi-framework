<?php

declare(strict_types=1);

namespace papi\Resource\Field;

/**
 * Postgresql "smallint"" column type
 */
class SmallInt extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'smallint';
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}
