<?php

declare(strict_types=1);

namespace papi\Validator;

/**
 * Checks if data is neither null or empty string
 */
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
