<?php

declare(strict_types=1);

namespace papi\Resource\Field;

class Time extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'time';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}
