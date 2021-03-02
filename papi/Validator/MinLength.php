<?php
declare(strict_types=1);

namespace papi\Validator;

class MinLength extends AbstractValidator
{
    private int $minLength;

    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    protected function isValid(mixed $data): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (! is_string($data) || strlen($data) < $this->minLength) {
            return false;
        }

        return true;
    }

    protected function getErrorMessage(): string
    {
        return "Must be a string equal or longer than $this->minLength chars";
    }
}