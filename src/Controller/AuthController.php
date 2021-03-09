<?php

declare(strict_types=1);

namespace App\Controller;

class AuthController extends \papi\Auth\AuthController
{
    protected function credentialsValid(?array $requestBody): bool
    {
        return true;
    }

    protected function getPayload(?array $requestBody): array
    {
        return [
            'user_id' => 2,
            'user_id_3' => '3',
        ];
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
