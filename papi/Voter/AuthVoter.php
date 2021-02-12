<?php
declare(strict_types=1);

namespace papi\Voter;

use papi\Auth\JWT;
use Workerman\Protocols\Http\Request;

class AuthVoter
{
    public static function hasValidToken(string $secret, Request $request): bool
    {
        return JWT::isValid($secret, $request->header('Authorization'));
    }

    public static function hasAttributesInPayload(string $secret, Request $request, array $attributes): bool
    {
        $token = $request->header('Authorization');
        if (! JWT::isValid($secret, $token)) {
            return false;
        }

        $payload = JWT::getPayload($token);
        foreach ($attributes as $key => $attribute) {
            if (! (isset($payload[$key])
                   && $payload[$key] === $attribute
            )) {
                return false;
            }
        }

        return true;
    }
}