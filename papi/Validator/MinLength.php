<?php
declare(strict_types=1);

namespace papi\Validator;

class MinLength extends AbstractValidator
{
    private int $minLength;

    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    public function getValidationErrors(string $field, mixed $data): ?string
    {
        if (is_null($data)) {
            return null;
        }

        if (! is_string($data) || strlen($data) < $this->minLength) {
            return "$field: must be a string equal or longer than $this->minLength chars";
        }

        return null;
    }
}