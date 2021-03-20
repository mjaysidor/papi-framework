<?php

declare(strict_types=1);

namespace papi\Resource\Field;

class Real extends Field
{
    protected function getDefaultProperties(): string
    {
        return "real";
    }

    public function getPHPTypeName(): string
    {
        return 'float';
    }
}
