<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Id extends Field
{
    protected function getDefaultProperties(): string
    {
        return "integer generated always as identity primary key";
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}