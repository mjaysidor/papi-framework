<?php

declare(strict_types=1);

namespace papi\Config;

/**
 * Contains default OpenAPI responses for various request methods
 */
abstract class APIResponsesConfig
{
    /**
     * Get response doc based on provided method
     *
     * @param string $method GET/POST/DELETE/PUT
     *
     * @return array
     */
    public function getResponses(string $method = 'GET'): array
    {
        switch ($method) {
            case 'GET':
                return $this->getGETResponses();
            case 'POST':
                return $this->getPOSTResponses();
            case 'DELETE':
                return $this->getDELETEResponses();
            case 'PUT':
                return $this->getPUTResponses();
        }

        return [];
    }

    /**
     * Get OpenAPI default GET response
     *
     * @return array
     */
    abstract public function getGETResponses(): array;

    /**
     * Get OpenAPI default POST response
     *
     * @return array
     */
    abstract public function getPOSTResponses(): array;

    /**
     * Get OpenAPI default PUT response
     *
     * @return array
     */
    abstract public function getPUTResponses(): array;

    /**
     * Get OpenAPI default DELETE response
     *
     * @return array
     */
    abstract public function getDELETEResponses(): array;

    /**
     * Get OpenAPI /auth (validation & JWT generation) endpoint response
     *
     * @return array
     */
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
