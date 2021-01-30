<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class TinyBlob extends Field
{
    public function getDefinition(): array
    {
        return [
            'TINYBLOB',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}