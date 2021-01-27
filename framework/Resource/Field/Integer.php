<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class Integer extends Field
{
    public function getDefinition(): array
    {
        return [
            'INT',
        ];
    }
    
    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}