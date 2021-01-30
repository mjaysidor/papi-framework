<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class MediumInt extends Field
{
    private ?int $length;

    public function __construct(?int $length = null, ?array $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    public function getDefinition(): array
    {
        $definition = $this->length ? "MEDIUMINT($this->length)" : 'MEDIUMINT';

        return [
            $definition,
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}