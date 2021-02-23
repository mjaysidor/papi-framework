<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class LongText extends Field
{
    public function getDefaultProperties(): string
    {
        return 'longtext';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}