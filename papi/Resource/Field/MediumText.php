<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class MediumText extends Field
{
    public function getDefinition(): array
    {
        return [
            'MEDIUMTEXT',
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}