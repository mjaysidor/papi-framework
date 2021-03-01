<?php
declare(strict_types=1);

namespace papi\Validator;

abstract class AbstractValidator
{
    abstract public function getValidationErrors(string $field, mixed $data): ?string;
}