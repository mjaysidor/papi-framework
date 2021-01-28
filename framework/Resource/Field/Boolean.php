<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class Boolean extends Field
{
    public function getDefinition(): array
    {
        return [
            'BINARY',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'boolean';
    }
}