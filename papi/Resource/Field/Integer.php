<?php

declare(strict_types=1);

namespace papi\Resource\Field;

class Integer extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'integer';
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}
