<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class TinyInt extends Field
{
    private ?int $length;

    public function __construct(?int $length = null, ?string $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    protected function getDefaultProperties(): string
    {
        return $this->length ? "TINYINT($this->length)" : 'TINYINT';
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}