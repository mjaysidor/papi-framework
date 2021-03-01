<?php
declare(strict_types=1);

namespace papi\Resource\Field;

abstract class Field
{
    private ?string $properties;

    public function __construct(?string $properties = null)
    {
        $this->properties = $properties;
    }

    public function getProperties(): string
    {
        $definition = $this->getDefaultProperties();
        if ($this->properties) {
            $definition .= ' '.$this->properties;
        }

        return $definition;
    }

    abstract public function getDefaultProperties(): string;

    abstract public function getPHPTypeName(): string;
}