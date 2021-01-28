<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class TinyInt extends Field
{
    private ?int $length;

    public function __construct(?int $length = null, ?array $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
    }

    public function getDefinition(): array
    {
        $definition = $this->length ? "TINYINT($this->length)" : 'TINYINT';

        return [
            $definition,
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'integer';
    }
}