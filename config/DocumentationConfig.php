<?php
declare(strict_types=1);

namespace config;

use papi\Documentation\DocumentationConfigGenerator;

class DocumentationConfig extends DocumentationConfigGenerator
{
    public function getOpenApiVersion(): string
    {
        return '3.0.0';
    }

    public function getDescription(): string
    {
        return 'The description of the application';
    }

    public function getTitle(): string
    {
        return 'App title';
    }

    public function getVersion(): string
    {
        return '1.0';
    }
}