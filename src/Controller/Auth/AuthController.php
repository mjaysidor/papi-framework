<?php
declare(strict_types=1);

namespace App\Controller\Auth;

class AuthController extends \papi\Auth\AuthController
{
    protected function credentialsValid(?array $requestBody): bool
    {
        return true;
    }

    protected function getPayload(?array $requestBody): array
    {
        return [];
    }

    protected function getOpenApiDocRequestBody(): array
    {
        return [
            'username' => [
                'type' => 'string',
            ],
            'password' => [
                'type' => 'string',
            ],
        ];
    }
}
