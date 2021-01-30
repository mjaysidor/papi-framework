<?php
declare(strict_types=1);

namespace papi\Relation;

class ManyToManyValidator
{
    public function getValidationErrors(
        ManyToMany $relation,
        array $data
    ): ?string {
        if (! (
            array_key_exists($relation->rootResourceIdField, $data)
            && array_key_exists($relation->relatedResourceIdField, $data)
        )) {
            return "Both IDs must be specified: $relation->rootResourceIdField, $relation->relatedResourceIdField";
        }

        $invalidFields = array_diff(
            array_keys($data),
            [
                $relation->rootResourceIdField,
                $relation->relatedResourceIdField,
            ]
        );

        if (! empty($invalidFields)) {
            $firstError = reset($invalidFields);

            return "Invalid field: $firstError";
        }

        return null;
    }
}