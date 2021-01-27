<?php
declare(strict_types=1);

namespace framework\Validator;

class NotBlank extends AbstractValidator
{
    public function getValidationErrors(string $field, $data): ?string
    {
        if ($data == null) {
            return "$field: cannot be blank";
        }

        return null;
    }
}