<?php

declare(strict_types=1);

namespace papi\Resource;

class Validator
{
    public function getValidationErrors(
        Resource $resource,
        array $data
    ): ?string {
        $editableFields = $resource->getEditableFields();

        foreach ($data as $field => $value) {
            if (! in_array($field, $editableFields, true)) {
                return "Invalid field: $field";
            }
        }

        $validators = $resource->getFieldValidators();

        foreach ($validators as $field => $fieldValidators) {
            foreach ($fieldValidators as $validator) {
                if (($validationError = $validator->getErrors($field, $data[$field] ?? null)) !== null) {
                    return $validationError;
                }
            }
        }

        return null;
    }
}
