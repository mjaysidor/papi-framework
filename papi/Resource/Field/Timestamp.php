<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Timestamp extends Field
{
    public function getDefaultProperties(): string
    {
        return 'TIMESTAMP';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}