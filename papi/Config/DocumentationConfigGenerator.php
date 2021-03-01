<?php /** @noinspection ALL */
declare(strict_types=1);

namespace papi\Config;

abstract class DocumentationConfigGenerator
{
    public function getOpenAPIHeaders(): array
    {
        return
            [
                'openapi' => $this->getOpenApiVersion(),
                'info'    => [
                    'description' => $this->getDescription(),
                    'title'       => $this->getTitle(),
                    'version'     => $this->getVersion(),
                ],
            ];
    }

    abstract public function getOpenApiVersion(): string;

    abstract public function getDescription(): string;

    abstract public function getTitle(): string;

    abstract public function getVersion(): string;
}