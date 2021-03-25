<?php

declare(strict_types=1);

namespace papi\Utils;

/**
 * Converts camelCase to snake_case
 */
class CaseConverter
{
    /**
     * Convert camelCase to snake_case
     *
     * @param string $camelCaseText
     *
     * @return string
     */
    public static function camelToSnake(string $camelCaseText): string
    {
        return strtolower(ltrim((string)preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $camelCaseText), '_'));
    }
}
