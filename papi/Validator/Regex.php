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

    protected function isValid(mixed $data): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (! is_string($data) || preg_match($this->regex, $data) !== 1) {
            return false;
        }

        return true;
    }

    protected function getErrorMessage(): string
    {
        return "Not a valid input - $this->errorLabel";
    }
}