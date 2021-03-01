<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Set extends Field
{
    private array $values;

    public function __construct(array $values, ?string $properties = null)
    {
        parent::__construct($properties);
        $this->values = $values;
    }

    public function getDefaultProperties(): string
    {
        return "SET(".implode(",", $this->values).")";
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}