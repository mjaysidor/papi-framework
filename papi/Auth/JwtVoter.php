<?php

declare(strict_types=1);

namespace papi\Auth;

use config\AuthConfig;
use Workerman\Protocols\Http\Request;

/**
 * Validates JSON Web Tokens
 */
abstract class JwtVoter
{
    protected bool $isValid;

    protected ?array $payload = null;

    public function __construct(Request $request)
    {
        $token = (string)$request->header('Authorization');
        if (($this->isValid = JWT::isValid(AuthConfig::getSecret(), $token)) === true) {
            $this->payload = JWT::getPayload($token);
        }
    }

    /**
     * Check if token is valid
     *
     * @return bool
     */
    public function hasValidToken(): bool
    {
        return $this->isValid;
    }

    /**
     * Check if token & payload are both valid
     *
     * @return bool
     */
    public function hasValidTokenAndPayload(): bool
    {
        return $this->isValid && $this->hasValidPayload();
    }

    /**
     * Check if JWT payload ($this->payload) contains required data (ex. $payload['admin'] = true)
     */
    abstract protected function hasValidPayload(): bool;
}
