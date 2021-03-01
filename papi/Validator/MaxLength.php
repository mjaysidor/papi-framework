<?php
declare(strict_types=1);

namespace papi\Validator;

class MaxLength extends AbstractValidator
{
    private int $maxLength;

    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function getValidationErrors(string $field, mixed $data): ?string
    {
        if (is_null($data)) {
            return null;
        }

        if (! is_string($data) || strlen($data) > $this->maxLength) {
            return "$field: must be a string no longer than $this->maxLength chars";
        }

        return null;
    }
}