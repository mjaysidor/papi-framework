<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Date extends Field
{
    public function getDefinition(): array
    {
        return [
            'DATE',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}