<?php
declare(strict_types=1);

namespace papi\Voter;

use config\AuthConfig;
use papi\Auth\JWT;
use Workerman\Protocols\Http\Request;

class AuthVoter
{
    public static function hasValidToken(
        Request $request
    ): bool {
        return JWT::isValid(AuthConfig::getSecret(), $request->header('Authorization'));
    }

    public static function hasAttributesInPayload(
        Request $request,
        array $attributes
    ): bool {
        $token = $request->header('Authorization');

        if (empty($token) || ! JWT::isValid(AuthConfig::getSecret(), $token)) {
            return false;
        }

        return array_intersect_assoc($attributes, JWT::getPayload($token)) === $attributes;
    }
}