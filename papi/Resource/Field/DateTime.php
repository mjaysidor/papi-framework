<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class DateTime extends Field
{
    public function getDefinition(): array
    {
        return [
            'DATETIME',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}