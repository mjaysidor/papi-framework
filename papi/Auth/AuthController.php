<?php /** @noinspection ALL */
declare(strict_types=1);

namespace papi\Auth;

use papi\Response\AccessDeniedResponse;
use papi\Response\JsonResponse;
use papi\Response\OKResponse;
use papi\Worker\App;

abstract class AuthController
{
    protected App $api;

    public function __construct(App $api)
    {
        $this->api = $api;
    }

    public function init(): void
    {
        $this->api->addDocumentedRoute(
            'POST',
            '/auth',
            function ($request) {
                return $this->getToken(json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR));
            },
            $this->getOpenApiDocRequestBody(),
            [],
            [
                200 => [
                    'description' => 'Retrieves resource data',
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
            ],
            'Authentication'
        );
    }

    protected function getToken(?array $body): JsonResponse
    {
        if (! $this->checkCredentials($body)) {
            return new AccessDeniedResponse('Invalid credentials');
        }

        return new OKResponse(['token' => JWT::encode($this->getSecret(), $this->getPayload($body))]);
    }

    abstract protected function checkCredentials(?array $body): bool;

    abstract protected function getPayload(?array $body): array;

    abstract protected function getSecret(): string;

    abstract protected function getOpenApiDocRequestBody(): array;
}