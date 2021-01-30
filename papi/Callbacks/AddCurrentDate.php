<?php
declare(strict_types=1);

namespace papi\Callbacks;

class AddCurrentDate implements PreExecutionBodyModifier
{
    private string $fieldName;

    private string $dateFormat;

    public function __construct(
        string $fieldName = 'created_at',
        string $dateFormat = 'Y-m-d H:i:s'
    ) {
        $this->fieldName = $fieldName;
        $this->dateFormat = $dateFormat;
    }

    public function modify(array &$data): void
    {
        $data[$this->fieldName] = (new \DateTime())->format($this->dateFormat);
    }
}