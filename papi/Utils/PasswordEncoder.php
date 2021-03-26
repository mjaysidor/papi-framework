<?php

declare(strict_types=1);

namespace papi\Utils;

/**
 * Encodes plain password into argon2id encoded password
 */
class PasswordEncoder
{
    /**
     * Encode password using argon2id algorithm
     *
     * @param string $plainPassword
     *
     * @return string
     */
    public static function encodePassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2ID);
    }
}
