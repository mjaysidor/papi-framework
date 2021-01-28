<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class Time extends Field
{
    public function getDefinition(): array
    {
        return [
            'TIME',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}