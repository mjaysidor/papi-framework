<?php

/** @noinspection ALL */

/** @noinspection ALL */

declare(strict_types=1);

namespace App\Controller;

class AuthController extends \papi\Auth\AuthController
{
    protected function checkCredentials(?array $body): bool
    {
        return true;
    }

    protected function getPayload(?array $body): array
    {
        return [
            'user_id' => 1,
        ];
    }

    protected function getSecret(): string
    {
        return 'secret';
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
