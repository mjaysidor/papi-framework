<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class BigInt extends Field
{
    public function getDefinition(): array
    {
        return [
            'BIGINT',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}