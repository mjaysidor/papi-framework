<?php
declare(strict_types=1);
namespace App\Voter;

use JsonException;
use papi\Auth\JwtVoter;

class UserLoggedInVoter extends JwtVoter
{
    /**
     * @throws JsonException
     */
    protected function hasValidPayload(): bool
    {
        return $this->isRoleUser();
    }

    public function getId(): ?string
    {
        return $this->payload['id'] ?? null;
    }

    /**
     * @throws JsonException
     */
    public function getRoles(): ?array
    {
        return $this->payload['roles'] ? json_decode($this->payload['roles'], true, 512, JSON_THROW_ON_ERROR) : null;
    }

    /**
     * @throws JsonException
     */
    public function isRoleUser(): bool
    {
        $roles = $this->getRoles();
        return !empty($roles) && in_array('ROLE_USER', $roles, true);
    }
}