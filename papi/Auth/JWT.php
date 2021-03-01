<?php
declare(strict_types=1);

namespace papi\Auth;

class JWT
{
    public static function encode(string $secret, array $payload = []): string
    {
        $base64UrlHeader = self::getEncodedHeader();
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));

        return $base64UrlHeader.".".$base64UrlPayload.".".self::getSignature(
                $base64UrlHeader,
                $base64UrlPayload,
                $secret
            );
    }

    public static function isValid(string $secret, ?string $token): bool
    {
        if (is_null($token)) {
            return false;
        }
        [$header, $payload, $signature] = explode('.', $token);

        return ! ($header !== self::getEncodedHeader()
                  || $signature !== self::getSignature(
                $header,
                $payload,
                $secret
            ));
    }

    public static function getPayload(string $token): array
    {
        return json_decode(base64_decode(explode('.', $token)[1]), true, 512, JSON_THROW_ON_ERROR);
    }

    private static function getSignature(string $base64UrlHeader, string $base64UrlPayload, string $secret): string
    {
        return self::base64UrlEncode(hash_hmac('sha256', $base64UrlHeader.".".$base64UrlPayload, $secret, true));
    }

    private static function getEncodedHeader(): string
    {
        return self::base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => 'HS256'], JSON_THROW_ON_ERROR));
    }

    private static function base64UrlEncode(string $string): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }
}