<?php
declare(strict_types=1);

namespace papi\Validator;

class Regex extends AbstractValidator
{
    private string $regex;

    private string $errorLabel;

    public function __construct(
        string $regex,
        string $errorLabel
    ) {
        $this->regex = $regex;
        $this->errorLabel = $errorLabel;
    }

    public function getValidationErrors(string $field, $data): ?string
    {
        if (is_null($data)) {
            return null;
        }

        if (! is_string($data) || preg_match($this->regex, $data) !== 1) {
            return "$field: please provide a valid input - $this->errorLabel";
        }

        return null;
    }
}