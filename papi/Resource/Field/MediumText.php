<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class MediumText extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'MEDIUMTEXT';
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}