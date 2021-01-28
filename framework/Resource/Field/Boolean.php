<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class Boolean extends Field
{
    public function getDefinition(): array
    {
        return [
            "BOOLEAN",
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'boolean';
    }
}