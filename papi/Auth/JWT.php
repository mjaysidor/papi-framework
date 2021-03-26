<?php

declare(strict_types=1);

namespace papi\Auth;

use JsonException;

/**
 * Creates, encodes & validates JSON Web Tokens
 *
 * @link: https://jwt.io/introduction
 */
class JWT
{
    /**
     * Encodes provided data into JWT
     *
     * @param string $secret
     * @param array  $payload
     *
     * @return string
     * @throws JsonException
     */
    public static function encode(
        string $secret,
        array $payload = []
    ): string {
        $base64UrlHeader = self::getEncodedHeader();
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . self::getSignature(
            $base64UrlHeader,
            $base64UrlPayload,
            $secret
        );
    }

    /**
     * Validates provided token
     *
     * @param string      $secret
     * @param string|null $token
     *
     * @return bool
     */
    public static function isValid(
        string $secret,
        ?string $token
    ): bool {
        if (empty($token) === true) {
            return false;
        }

        [$header, $payload, $signature] = explode('.', $token);

        return $signature === self::getSignature(
            $header,
            $payload,
            $secret
        );
    }

    /**
     * Gets payload (data) contained in provided token
     *
     * @param string $token
     *
     * @return array
     * @throws JsonException
     */
    public static function getPayload(string $token): array
    {
        return json_decode(base64_decode(explode('.', $token)[1]), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Calculates JWT signature based on provided header, payload & secret
     *
     * @param string $base64UrlHeader
     * @param string $base64UrlPayload
     * @param string $secret
     *
     * @return string
     */
    private static function getSignature(
        string $base64UrlHeader,
        string $base64UrlPayload,
        string $secret
    ): string {
        return self::base64UrlEncode(hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true));
    }

    /**
     * Returns base64url encoded token header
     *
     * @return string
     * @throws JsonException
     */
    private static function getEncodedHeader(): string
    {
        return self::base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR));
    }

    /**
     * Converts base64 encoded content into base64url format
     *
     * @param string $string
     *
     * @return string
     */
    private static function base64UrlEncode(string $string): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }
}
