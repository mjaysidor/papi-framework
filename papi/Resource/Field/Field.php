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

        if ($this->properties !== null) {
            return "$definition $this->properties";
        }

        return $definition;
    }

    abstract protected function getDefaultProperties(): string;

    abstract public function getPHPTypeName(): string;
}
