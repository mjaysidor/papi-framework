<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class Id extends Field
{
    public function getDefinition(): array
    {
        return [
            "INT",
            "NOT NULL",
            "AUTO_INCREMENT",
            "PRIMARY KEY",
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}