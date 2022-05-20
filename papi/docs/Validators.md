### Overview:
Validators are used to validate data on incoming resource POST/PUT requests. In getPUTValidators/getPOSTValidators methods of resource class validators are specified in following format: 'field_name' => [new SomeValidator(options), new SomeOtherValidator(options)]. Each validator class extends AbstractValidator and contains 2 methods - isValid, which performs any checks to confirm or deny provided data validity, and getErrorMessage, which specifies what message is returned to client on validation failure, so you can create as many custom validators, as you'd like.

### Example use of validators:
* Resource class:
```
return [
            [
                'content' => [
                    new MinLength(10),
                    new NotBlank(),
                ],
                'email' => [
                    new Email()
                ]
            ],
        ];
```
* Validator class:
```
/**
 * Checks if data is neither null or empty string
 */
class NotBlank extends AbstractValidator
{
    protected function isValid(mixed $data): bool
    {
        return $data !== null && $data !== '';
    }

    protected function getErrorMessage(): string
    {
        return 'Cannot be blank';
    }
}
```
```
/**
 * Checks if data is no shorter than N characters
 */
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
```

### Available validators:
* **Email()**
* **InArray(array $allowedValues)**
* **MaxLength(int $maxLengthOfString)**
* **MinLength(int $minLengthOfString)**
* **NotBlank()** - not null, nor empty string
* **NotNull()**
* **PositiveInteger()** - integer no less than zero
* **Regex(string $regex)** - string compliant with provided regular expression
* **Url()** - valid URL string