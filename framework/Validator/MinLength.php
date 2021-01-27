<?php
declare(strict_types=1);

namespace framework\Validator;

use JetBrains\PhpStorm\Pure;

class MinLength extends AbstractValidator
{
    private int $minLength;

    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    #[Pure] public function getValidationErrors(string $field, $data): ?string
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