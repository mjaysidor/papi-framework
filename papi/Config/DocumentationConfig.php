<?php

declare(strict_types=1);

namespace papi\Config;

/**
 * Provides OpenAPI documentation settings
 */
abstract class DocumentationConfig
{
    /**
     * Returns OpenAPI documentation headers (info, description, version, etc.)
     *
     * @return array
     */
    public function getOpenAPIHeaders(): array
    {
        return
            [
                'openapi' => $this->getOpenApiVersion(),
                'info'    => [
                    'description' => $this->getAppDescription(),
                    'title'       => $this->getAppTitle(),
                    'version'     => $this->getAppVersion(),
                ],
            ];
    }

    /**
     * Return OpenAPI version (ex. 3.0.0)
     *
     * @return string
     */
    abstract public function getOpenApiVersion(): string;

    /**
     * Return OpenAPI app description (ex. "API used for exchanging messages")
     *
     * @return string
     */
    abstract public function getAppDescription(): string;

    /**
     * Return OpenAPI app title/name (ex. "Messenger")
     *
     * @return string
     */
    abstract public function getAppTitle(): string;

    /**
     * Return app version (ex. 1.0.7)
     *
     * @return string
     */
    abstract public function getAppVersion(): string;
}
