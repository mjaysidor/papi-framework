<?php

declare(strict_types=1);

namespace papi\Worker;

use config\APIResponses;

/**
 * Contains Papi route data
 */
class Route
{
    private string $path;

    private string $method;

    private string $tag;

    private ?array $responses;

    private ?array $requestBody;

    private ?array $parameters;

    /**
     * @var callable
     */
    private $handler;

    public function __construct(
        string $path,
        string $method,
        callable $callback,
        string $resourceName = 'Other',
        ?array $responses = null,
        ?array $requestBody = null,
        ?array $parameters = null,
    ) {
        $this->path = $path;
        $this->method = $method;
        $this->tag = $resourceName;
        if (empty($responses)) {
            $this->responses = (new APIResponses())->getResponses($method);
        } else {
            $this->responses = $responses;
        }
        $this->requestBody = $requestBody;
        $this->parameters = $parameters;
        $this->handler = $callback;
    }

    /**
     * Returns OpenApi Documentation of the route
     *
     * @return array
     */
    public function getRouteOpenApiDoc(): array
    {
        $array = [];
        if ($requestBody = $this->requestBody) {
            $array['requestBody']
                = [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type'       => 'object',
                            'properties' => $requestBody,
                        ],
                    ],
                ],
            ];
        }

        if (! empty($parameters = $this->parameters)) {
            $array['parameters'] = $parameters;
        }

        $array['responses'] = $this->responses;
        $array['tags'] = [$this->tag];

        return $array;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHandler(): callable
    {
        return $this->handler;
    }
}
