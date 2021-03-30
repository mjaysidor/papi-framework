<?php

declare(strict_types=1);

namespace config;

class DocumentationConfig extends \papi\Config\DocumentationConfig
{
    public function getOpenApiVersion(): string
    {
        return '3.0.0';
    }

    public function getAppDescription(): string
    {
        return 'The description of the application';
    }

    public function getAppTitle(): string
    {
        return 'App title';
    }

    public function getAppVersion(): string
    {
        return '1.0';
    }
}
