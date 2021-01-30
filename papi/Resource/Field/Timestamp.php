<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Timestamp extends Field
{
    public function getDefinition(): array
    {
        return [
            'TIMESTAMP',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}