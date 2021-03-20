<?php

declare(strict_types=1);

namespace papi\Validator;

class NotBlank extends AbstractValidator
{
    protected function isValid(mixed $data): bool
    {
        return $data !== null && $data !== '';
    }

    protected function getErrorMessage(): string
    {
        return 'Cannot be blank';
    }
}
