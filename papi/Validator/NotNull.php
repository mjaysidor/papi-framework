<?php
declare(strict_types=1);

namespace papi\Validator;

class NotNull extends AbstractValidator
{
    public function getValidationErrors(string $field, $data): ?string
    {
        if (is_null($data)) {
            return "$field: cannot be null";
        }

        return null;
    }
}