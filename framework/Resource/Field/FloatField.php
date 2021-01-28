<?php
declare(strict_types=1);

namespace framework\Resource\Field;

class FloatField extends Field
{
    private int $length;

    private int $precision;

    public function __construct(int $length, int $precision, ?array $properties = null)
    {
        parent::__construct($properties);
        $this->length = $length;
        $this->precision = $precision;
    }

    public function getDefinition(): array
    {
        return [
            "FLOAT($this->length,$this->precision)",
        ];
    }

    public function getPHPTypeName(): string
    {
        return 'float';
    }
}