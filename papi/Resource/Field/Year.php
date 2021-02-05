<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Year extends Field
{
    public function getDefaultProperties(): string
    {
        return 'YEAR';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}