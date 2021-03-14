<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class TimeInterval extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'interval';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}