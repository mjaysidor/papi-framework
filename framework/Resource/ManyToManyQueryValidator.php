<?php
declare(strict_types=1);

namespace framework\Resource;

use framework\Relation\ManyToMany;

class ManyToManyQueryValidator
{
    public function getValidationErrors(
        ManyToMany $relation,
        array $data
    ): ?string {
        if ($error = $this->getInvalidQueryFields($relation, $data)) {
            return $error;
        }

        return null;
    }

    private function getInvalidQueryFields(
        ManyToMany $relation,
        array $data
    ): ?string {
        if (empty($data)) {
            return "Query must contain at least one of following parameters: $relation->rootResourceIdField, $relation->relatedResourceIdField";
        }
        $invalidFields = array_diff(
            array_keys($data),
            [
                'cursor',
                'order',
                'orderBy',
                $relation->rootResourceIdField,
                $relation->relatedResourceIdField,
            ]
        );

        if (! empty($invalidFields)) {
            $firstError = reset($invalidFields);

            return "Invalid query: $firstError";
        }

        return null;
    }
}