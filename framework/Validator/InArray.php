<?php
declare(strict_types=1);

namespace framework\Validator;

class InArray extends AbstractValidator
{
    private array $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function getValidationErrors(string $field, $data): ?string
    {
        if (is_null($data)) {
            return null;
        }

        if (! in_array($data, $this->array, true)) {
            return "$field: is not in specified array of values";
        }

        return null;
    }
}