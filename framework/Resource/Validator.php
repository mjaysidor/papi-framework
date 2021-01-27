<?php
declare(strict_types=1);

namespace framework\Resource;

class Validator
{
    public function getValidationErrors(
        Resource $resource,
        array $data
    ): ?string {
        if ($error = $this->getEmptyBodyError($data)) {
            return $error;
        }

        if ($error = $this->getInvalidFields($resource, $data)) {
            return $error;
        }

        foreach ($resource->getFieldValidators() as $field => $validators) {
            foreach ($validators as $validator) {
                $validationErrors = $validator->getValidationErrors($field, $data[$field] ?? null);

                if ($validationErrors) {
                    return $validationErrors;
                }
            }
        }

        return null;
    }

    private function getInvalidFields(
        Resource $resource,
        array $data
    ): ?string {
        $invalidFields = array_diff(
            array_keys($data),
            array_values($resource->getEditableFields())
        );

        if (! empty($invalidFields)) {
            $firstError = reset($invalidFields);

            return "Invalid field: $firstError";
        }

        return null;
    }

    private function getEmptyBodyError(
        array $data
    ): ?string {
        if (empty($data)) {
            return 'Body cannot be empty';
        }

        return null;
    }
}