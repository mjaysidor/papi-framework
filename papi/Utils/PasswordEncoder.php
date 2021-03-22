<?php
declare(strict_types=1);

namespace papi\Utils;

class PasswordEncoder
{
    public static function encodePassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2ID);
    }
}
