<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class LongBlob extends Field
{
    public function getDefinition(): array
    {
        return [
            'LONGBLOB',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}