<?php
declare(strict_types=1);

namespace papi\Validator;

class NotNull extends AbstractValidator
{
    protected function isValid(mixed $data): bool
    {
        return ! is_null($data);
    }

    protected function getErrorMessage(): string
    {
        return 'Cannot be null';
    }
}