<?php
declare(strict_types=1);

namespace papi\Validator;

class Email extends AbstractValidator
{
    public function getValidationErrors(string $field, $data): ?string
    {
        if (is_null($data)) {
            return null;
        }

        if (filter_var($data, FILTER_VALIDATE_EMAIL) === false) {
            return "$field: is not a valid email address";
        }

        return null;
    }
}