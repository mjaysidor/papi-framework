<?php

declare(strict_types=1);

namespace papi\Validator;

/**
 * Checks validity of provided data
 */
abstract class AbstractValidator
{
    /**
     * Returns validation errors if present
     *
     * @param string $fieldName
     * @param mixed  $data
     *
     * @return string|null
     */
    public function getErrors(string $fieldName, mixed $data): ?string
    {
        if ($this->isValid($data) === false) {
            return "$fieldName => " . $this->getErrorMessage();
        }

        return null;
    }

    /**
     * Check whether provided data is valid
     *
     * @param mixed $data
     *
     * @return bool
     */
    abstract protected function isValid(mixed $data): bool;

    /**
     * Return error message displayed on validation failure
     *
     * @return string
     */
    abstract protected function getErrorMessage(): string;
}
