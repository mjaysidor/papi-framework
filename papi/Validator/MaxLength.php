<?php
declare(strict_types=1);

namespace papi\Validator;

class MaxLength extends AbstractValidator
{
    private int $maxLength;

    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    protected function isValid(mixed $data): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (! is_string($data) || strlen($data) > $this->maxLength) {
            return false;
        }

        return true;
    }

    protected function getErrorMessage(): string
    {
        return "Must be a string no longer than $this->maxLength chars";
    }
}