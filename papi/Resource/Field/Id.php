<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Id extends Field
{
    public function getDefaultProperties(): string
    {
        return "INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY";
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}