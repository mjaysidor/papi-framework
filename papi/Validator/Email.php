<?php
declare(strict_types=1);

namespace papi\Validator;

class Email extends AbstractValidator
{
    protected function isValid(mixed $data): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (filter_var($data, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        return true;
    }

    protected function getErrorMessage(): string
    {
        return 'Not a valid email address';
    }
}