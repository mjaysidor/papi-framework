<?php

declare(strict_types=1);

namespace papi\Resource\Field;

class Text extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'text';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}
