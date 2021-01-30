<?php
declare(strict_types=1);

namespace papi\Resource\Field;

abstract class Field
{
    private ?array $properties;

    public function __construct(array $properties = null)
    {
        $this->properties = $properties;
    }

    public function getProperties(): array
    {
        $properties = $this->getDefinition();

        if ($this->properties) {
            $properties = array_merge(
                $this->getDefinition(),
                [
                    $this->properties,
                ]
            );
        }

        return $properties;
    }

    abstract public function getDefinition(): array;

    abstract public function getPHPTypeName(): string;
}