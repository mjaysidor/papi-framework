<?php
declare(strict_types=1);

namespace framework\Worker;

class App extends \Mark\App
{
    public function getRouteInfo(): array
    {
        return $this->routeInfo;
    }

    public function addDocumentedRoute(
        $method,
        $path,
        $callback,
        array $requestBody = [],
        array $urlParameters = [],
        array $responses = []
    ): void {
        $this->routeInfo[$method][] = [
            $this->pathPrefix.$path,
            $callback,
            'responses'  => $responses,
            'body'       => $requestBody,
            'parameters' => $urlParameters,
        ];
    }
}