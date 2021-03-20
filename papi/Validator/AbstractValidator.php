<?php

declare(strict_types=1);

namespace papi\Validator;

abstract class AbstractValidator
{
    public function getErrors(string $fieldName, mixed $data): ?string
    {
        if ($this->isValid($data) === false) {
            return "$fieldName => " . $this->getErrorMessage();
        }

        return null;
    }

    abstract protected function isValid(mixed $data): bool;

    abstract protected function getErrorMessage(): string;
}
