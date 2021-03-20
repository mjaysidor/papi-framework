<?php

declare(strict_types=1);

namespace papi\Validator;

class InArray extends AbstractValidator
{
    private array $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    protected function isValid(mixed $data): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (! in_array($data, $this->array, true)) {
            return false;
        }

        return true;
    }

    protected function getErrorMessage(): string
    {
        return 'Not in specified array of values: [' . implode(',', $this->array) . ']';
    }
}
