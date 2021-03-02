<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class Enum extends Field
{
    private array $values;

    public function __construct(array $values, ?string $properties = null)
    {
        parent::__construct($properties);
        $this->values = $values;
    }

    protected function getDefaultProperties(): string
    {
        return "ENUM(".implode(",", $this->values).")";
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}