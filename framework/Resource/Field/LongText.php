<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class LongText extends Field
{
    public function getDefinition(): array
    {
        return [
            'LONGTEXT',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}