<?php

declare(strict_types=1);

namespace papi\Resource\Field;

/**
 * Contains SQL definition of a resource field
 */
abstract class Field
{
    private ?string $properties;

    public function __construct(?string $properties = null)
    {
        $this->properties = $properties;
    }

    /**
     * Returns final SQL definition of the field
     *
     * @return string
     */
    public function getProperties(): string
    {
        $definition = $this->getDefaultProperties();

        if ($this->properties !== null) {
            return "$definition $this->properties";
        }

        return $definition;
    }

    /**
     * Returns default properties of a field type (ex. "integer generated always as identity primary key")
     *
     * @return string
     */
    abstract protected function getDefaultProperties(): string;

    /**
     * Returns corresponding PHP variable type name (ex. string/float)
     *
     * @return string
     */
    abstract public function getPHPTypeName(): string;
}
