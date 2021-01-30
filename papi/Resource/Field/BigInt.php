<?php
declare(strict_types=1);

namespace papi\Resource\Field;

class BigInt extends Field
{
    private ?int $length;

    public function __construct(?int $length = null, ?array $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    public function getDefinition(): array
    {
        $definition = $this->length ? "BIGINT($this->length)" : 'BIGINT';

        return [
            $definition,
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}