<?php
declare(strict_types=1);

namespace config;

use framework\Config\APIResponsesConfig;

class APIResponses implements APIResponsesConfig
{
    public static function getGETResponses(): array
    {
        return [
            200 => [
                'description' => 'Retrieves resource data',
            ],
            404 => [
                'description' => 'Resource not found',
            ],
        ];
    }

    public static function getPOSTResponses(): array
    {
        return [
            201 => [
                'description' => 'Resource created',
            ],
            400 => [
                'description' => 'Invalid body',
            ],
        ];
    }

    public static function getPUTResponses(): array
    {
        return [
            200 => [
                'description' => 'Resource updated',
            ],
            400 => [
                'description' => 'Invalid body',
            ],
            404 => [
                'description' => 'Resource not found',
            ],
        ];
    }

    public static function getDELETEResponses(): array
    {
        return [
            204 => [
                'description' => 'Resource deleted',
            ],
            404 => [
                'description' => 'Resource not found',
            ],
        ];
    }
}