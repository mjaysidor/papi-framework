<?php
declare(strict_types=1);

namespace config;

class DocumentationConfig
{
    public static function getHeaders(): array
    {
        return array_merge(
            self::getOpenApiVersion(),
            self::getInfo(),
        );
    }

    public static function getOpenApiVersion(): array
    {
        return [
            'openapi' => '3.0.0',
        ];
    }

    public static function getInfo(): array
    {
        return [
            'info' => [
                'description' => self::getDescription(),
                'title'       => self::getTitle(),
                'version'     => self::getVersion(),
            ],
        ];
    }

    public static function getDescription(): string
    {
        return 'app info';
    }

    public static function getTitle(): string
    {
        return 'title';
    }

    public static function getVersion(): string
    {
        return '0.1';
    }
}