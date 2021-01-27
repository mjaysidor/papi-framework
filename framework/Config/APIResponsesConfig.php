<?php
declare(strict_types=1);

namespace framework\Config;

interface APIResponsesConfig
{
    public static function getGETResponses(): array;

    public static function getPOSTResponses(): array;

    public static function getPUTResponses(): array;

    public static function getDELETEResponses(): array;
}