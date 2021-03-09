<?php
declare(strict_types=1);

namespace papi\Auth;

use config\APIResponses;
use config\AuthConfig;
use papi\Response\AccessDeniedResponse;
use papi\Response\JsonResponse;
use papi\Response\OKResponse;
use papi\Worker\App;

/**
 * Creates endpoint used for generating JSON Web Tokens for validated users
 * More at: https://jwt.io/introduction
 */
abstract class AuthController
{
    protected App $api;

    public function __construct(App $api)
    {
        $this->api = $api;
    }

    public function init(): void
    {
        $this->api->addRoute(
            'POST',
            '/auth',
            function ($request) {
                return $this->getToken(json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR));
            },
            $this->getOpenApiDocRequestBody(),
            [],
            (new APIResponses())->getAuthResponses(),
            'Auth'
        );
    }

    protected function getToken(?array $requestBody): JsonResponse
    {
        if (! $this->credentialsValid($requestBody)) {
            return new AccessDeniedResponse('Invalid credentials');
        }

        return new OKResponse(
            [
                'token' => JWT::encode(
                    AuthConfig::getSecret(),
                    $this->getPayload($requestBody)
                ),
            ]
        );
    }

    /**
     * Check if request credentials provided by user are valid
     * (ex. check provided username & password against DB table 'users')
     */
    abstract protected function credentialsValid(?array $requestBody): bool;

    /**
     * Return payload to be contained in JWT after successful validation
     */
    abstract protected function getPayload(?array $requestBody): array;

    /**
     * Return array containing required request body fields stored in OpenAPI format
     */
    abstract protected function getOpenApiDocRequestBody(): array;
}