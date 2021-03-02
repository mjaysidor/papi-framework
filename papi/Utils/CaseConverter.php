<?php
declare(strict_types=1);

namespace papi\Utils;

class CaseConverter
{
    public static function camelToSnake(string $camelCaseText): string
    {
        return strtolower(ltrim((string)preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $camelCaseText), '_'));
    }
}