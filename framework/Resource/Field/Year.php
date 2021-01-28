<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class Year extends Field
{
    public function getDefinition(): array
    {
        return [
            'YEAR',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}