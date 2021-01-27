<?php
declare(strict_types=1);

namespace framework\Validator;

class Url extends AbstractValidator
{
    public const URL_REGEX = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';

    public function getValidationErrors(string $field, $data): ?string
    {
        if (is_null($data)) {
            return null;
        }

        if (! is_string($data) || preg_match(self::URL_REGEX, $data) !== 1) {
            return "$field: please provide a valid URL";
        }

        return null;
    }
}