<?php
declare(strict_types=1);

namespace framework\Worker;

use config\APIResponses;

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
            "POST" => APIResponses::getPOSTResponses(),
            "GET" => APIResponses::getGETResponses(),
            "PUT" => APIResponses::getPUTResponses(),
            "DELETE" => APIResponses::getDELETEResponses(),
        };

        foreach ($methods as $m) {
            $this->routeInfo[$m][] = [$this->pathPrefix.$path, $callback, $responses, $body, $urlParameters];
        }
    }
    
}