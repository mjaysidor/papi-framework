<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Text extends Field
{
    private int $length;

    public function __construct(int $length, ?array $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    public function getDefaultProperties(): string
    {
        return "TEXT($this->length)";
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}