<?php
declare(strict_types=1);

namespace framework\Validator;

use JetBrains\PhpStorm\Pure;

class PositiveInteger extends AbstractValidator
{
    #[Pure] public function getValidationErrors(string $field, $data): ?string
    {
        if (is_null($data)) {
            return null;
        }

        if (! is_int($data) || $data < 0) {
            return "$field: must be a positive integer";
        }

        return null;
    }
}