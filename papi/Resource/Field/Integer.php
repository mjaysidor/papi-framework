<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Integer extends Field
{
    private ?int $length;

    public function __construct(?int $length = null, ?string $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    protected function getDefaultProperties(): string
    {
        return $this->length ? "INT($this->length)" : 'INT';
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}