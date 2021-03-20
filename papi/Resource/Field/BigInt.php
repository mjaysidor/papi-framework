<?php

declare(strict_types=1);

namespace papi\Resource\Field;

class BigInt extends Field
{
    protected function getDefaultProperties(): string
    {
        return 'bigint';
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}
