<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Time extends Field
{
    public function getDefaultProperties(): string
    {
        return 'TIME';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}