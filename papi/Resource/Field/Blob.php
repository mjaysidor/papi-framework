<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Blob extends Field
{
    private int $length;

    public function __construct(int $length, ?string $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    public function getDefaultProperties(): string
    {
        return "BLOB($this->length)";
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}