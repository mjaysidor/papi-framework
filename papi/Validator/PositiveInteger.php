<?php
declare(strict_types=1);

namespace papi\Validator;

class PositiveInteger extends AbstractValidator
{
    public function getValidationErrors(string $field, mixed $data): ?string
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