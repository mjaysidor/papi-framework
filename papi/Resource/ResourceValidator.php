<?php

declare(strict_types=1);

namespace papi\Resource;

class ResourceValidator
{
    public function getPOSTValidationErrors(
        Resource $resource,
        array $data
    ): ?string {
        $resourceFields = array_keys($resource->getFields());

        foreach ($data as $field => $value) {
            if ($field === 'id' || ! in_array($field, $resourceFields, true)) {
                return "Invalid field: $field";
            }
        }

        $validators = $resource->getPOSTValidators();

        foreach ($validators as $field => $fieldValidators) {
            foreach ($fieldValidators as $validator) {
                if (($validationError = $validator->getErrors($field, $data[$field] ?? null)) !== null) {
                    return $validationError;
                }
            }
        }

        return null;
    }

    public function getPUTValidationErrors(
        Resource $resource,
        array $data
    ): ?string {
        $editableFields = $resource->getEditableFields();

        foreach ($data as $field => $value) {
            if (! in_array($field, $editableFields, true)) {
                return "Invalid field: $field";
            }
        }

        $validators = $resource->getPUTValidators();

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
