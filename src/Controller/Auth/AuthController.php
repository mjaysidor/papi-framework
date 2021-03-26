<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Resource\Auth\User;
use papi\Utils\PasswordEncoder;

class AuthController extends \papi\Auth\AuthController
{
    private array $userData;

    protected function credentialsValid(?array $requestBody): bool
    {
        if (isset($requestBody['username'], $requestBody['password']) === false) {
            return false;
        }
        $user = (new User())->get(
            [
                'username' => $requestBody['username'],
            ],
            ['*']
        );

        if (isset($user[0]) !== true) {
            return false;
        }

        $this->userData = $user[0];

        return password_verify($requestBody['password'], $this->userData['password']);
    }

    protected function getPayload(?array $requestBody): array
    {
        return [
            'roles' => $this->userData['roles']
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
