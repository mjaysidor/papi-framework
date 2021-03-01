<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class MediumInt extends Field
{
    private ?int $length;

    public function __construct(?int $length = null, ?string $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    public function getDefaultProperties(): string
    {
        return $this->length ? "MEDIUMINT($this->length)" : 'MEDIUMINT';
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}