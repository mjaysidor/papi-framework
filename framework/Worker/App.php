<?php
declare(strict_types=1);

namespace framework\Worker;

class App extends \Mark\App
{
    public function getRouteInfo(): array
    {
        return $this->routeInfo;
    }

    public function addDocumentedRoute($method, $path, $callback, array $body = [], array $urlParameters = []): void
    {
        $methods = (array)$method;

        $responses = match ($method) {
            "POST" => $this->getPOSTResponses(),
            "GET" => $this->getGETResponses(),
            "PUT" => $this->getPUTResponses(),
            "DELETE" => $this->getDELETEResponses(),
        };

        foreach ($methods as $m) {
            $this->routeInfo[$m][] = [$this->pathPrefix.$path, $callback, $responses, $body, $urlParameters];
        }
    }

    private function getGETResponses(): array
    {
        return [
            200 => [
                'description' => 'Retrieves resource data',
            ],
            404 => [
                'description' => 'Resource not found',
            ],
        ];
    }

    private function getPOSTResponses(): array
    {
        return [
            '201' => [
                'description' => 'Resource created',
            ],
            400   => [
                'description' => 'Invalid body',
            ],
        ];
    }

    private function getPUTResponses(): array
    {
        return [
            200 => [
                'description' => 'Resource updated',
            ],
            400 => [
                'description' => 'Invalid body',
            ],
            404 => [
                'description' => 'Resource not found',
            ],
        ];
    }

    private function getDELETEResponses(): array
    {
        return [
            204 => [
                'description' => 'Resource deleted',
            ],
            404 => [
                'description' => 'Resource not found',
            ],
        ];
    }
}