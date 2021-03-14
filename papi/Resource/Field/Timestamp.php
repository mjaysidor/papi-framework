<?php
declare(strict_types=1);

namespace papi\Resource\Field;

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