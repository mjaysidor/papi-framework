<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class SmallInt extends Field
{
    public function getDefinition(): array
    {
        return [
            'SMALLINT',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}