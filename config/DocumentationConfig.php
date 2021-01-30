<?php
declare(strict_types=1);

namespace config;

use papi\Config\DocumentationConfigGenerator;

class DocumentationConfig extends DocumentationConfigGenerator
{
    public function getOpenApiVersion(): string
    {
        return '3.0.0';
    }

    public function getDescription(): string
    {
        return 'Blog blog blog';
    }

    public function getTitle(): string
    {
        return 'I.H.M.J.';
    }

    public function getVersion(): string
    {
        return '0.1';
    }
}