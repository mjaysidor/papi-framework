<?php

declare(strict_types=1);

namespace papi\Resource\Field;

class Char extends Field
{
    private int $length;

    public function __construct(int $length, ?string $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    protected function getDefaultProperties(): string
    {
        return "char($this->length)";
    }

    public function getPHPTypeName(): string
    {
        return 'char';
    }
}
