<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class TinyText extends Field
{
    public function getDefinition(): array
    {
        return [
            'TINYTEXT',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}