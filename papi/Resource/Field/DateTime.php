<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class DateTime extends Field
{
    public function getDefaultProperties(): string
    {
        return 'DATETIME';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}