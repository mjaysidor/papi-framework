<?php

declare(strict_types=1);

namespace config;

use papi\Config\APIResponsesConfig;

class APIResponses extends APIResponsesConfig
{
    public function getGETResponses(array $body = []): array
    {
        return [
            200 => [
                'description' => 'Retrieves resource data',
                'content'     => [
                    'application/json' => [
                        'schema' => [
                            'type'       => 'object',
                            'properties' => $body,
                        ],
                    ],
                ],
            ],
            404 => [
                'description' => 'Resource not found',
            ],
        ];
    }

    public function getPOSTResponses(): array
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

    public function getPUTResponses(): array
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

    public function getDELETEResponses(): array
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

    public function getAuthResponses(): array
    {
        return [
            200 => [
                'description' => 'Returns JSON Web Token (JWT)',
                'content'     => [
                    'application/json' => [
                        'schema' => [
                            'type'       => 'object',
                            'properties' => [
                                'token' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            403 => [
                'description' => 'Invalid credentials',
            ],
        ];
    }
}
