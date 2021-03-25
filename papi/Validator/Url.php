<?php

declare(strict_types=1);

namespace papi\Validator;

/**
 * Checks if data is a valid URL
 */
class Url extends AbstractValidator
{
    public const URL_REGEX = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';

    protected function isValid(mixed $data): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (! is_string($data) || preg_match(self::URL_REGEX, $data) !== 1) {
            return false;
        }

        return true;
    }

    protected function getErrorMessage(): string
    {
        return "Not a valid URL";
    }
}
