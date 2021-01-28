<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class Set extends Field
{
    private array $values;

    public function __construct(array $values, ?array $properties = null)
    {
        parent::__construct($properties);
        $this->values = $values;
    }

    public function getDefinition(): array
    {
        return [
            "SET(".implode(",", $this->values).")",
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'string';
    }
}