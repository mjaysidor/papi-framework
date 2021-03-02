<?php
declare(strict_types=1);

namespace papi\Validator;

class PositiveInteger extends AbstractValidator
{
    protected function isValid(mixed $data): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (! is_int($data) || $data < 0) {
            return false;
        }

        return true;
    }

    protected function getErrorMessage(): string
    {
        return 'Must be a positive integer';
    }
}