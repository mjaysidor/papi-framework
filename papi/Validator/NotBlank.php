<?php /** @noinspection TypeUnsafeComparisonInspection */
declare(strict_types=1);

namespace papi\Validator;

class NotBlank extends AbstractValidator
{
    public function getValidationErrors(string $field, mixed $data): ?string
    {
        if ($data == null) {
            return "$field: cannot be blank";
        }

        return null;
    }
}