<?php
declare(strict_types=1);

namespace framework\Resource;

class ResourceQueryValidator
{
    public function getValidationErrors(
        Resource $resource,
        array $data
    ): ?string {
        if ($error = $this->getInvalidQueryFields($resource, $data)) {
            return $error;
        }

        return null;
    }

    private function getInvalidQueryFields(
        Resource $resource,
        array $data
    ): ?string {
        $invalidFields = array_diff(
            array_keys($data),
            array_merge(
                array_keys($resource->getFields()),
                [
                    'cursor',
                    'order',
                    'orderBy',
                ]
            )
        );

        if (! empty($invalidFields)) {
            $firstError = reset($invalidFields);

            return "Invalid query: $firstError";
        }

        return null;
    }
}