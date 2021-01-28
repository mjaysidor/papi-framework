<?php
declare(strict_types=1);

namespace framework\Controller;

use Closure;
use framework\Worker\App;

abstract class RESTController
{
    protected App $api;

    public string $resourceName;

    public function __construct(App $api)
    {
        $this->api = $api;
    }

    abstract public function init(): void;

    abstract public function getEndpoint(): string;

    abstract public function getEndpointIds(): array;

    public function getEndpointWithId(): string
    {
        $url = $this->getEndpoint();
        foreach ($this->getEndpointIds() as $id) {
            $url .= '/{'.$id.'}';
        }

        return $url;
    }

    abstract public function getPOSTPUTBody(): array;

    abstract public function getGETResponseBody(): array;

    protected function post(Closure $callback): void
    {
        $this->api->addDocumentedRoute(
            'POST',
            $this->getEndpoint(),
            $callback,
            $this->getPOSTPUTBody()
        );
    }

    protected function getById(Closure $callback): void
    {
        $this->api->addDocumentedRoute(
            'GET',
            $this->getEndpointWithId(),
            $callback,
            [],
            $this->getEndpointIds(),
            $this->getGETResponseBody()
        );
    }

    protected function get(Closure $callback): void
    {
        $this->api->addDocumentedRoute(
            'GET',
            $this->getEndpoint(),
            $callback,
            [],
            [],
            $this->getGETResponseBody()
        );
    }

    protected function delete(Closure $callback): void
    {
        $this->api->addDocumentedRoute(
            'DELETE',
            $this->getEndpointWithId(),
            $callback,
            [],
            $this->getEndpointIds()
        );
    }

    protected function put(Closure $callback): void
    {
        $this->api->addDocumentedRoute(
            'PUT',
            $this->getEndpointWithId(),
            $callback,
            $this->getPOSTPUTBody(),
            $this->getEndpointIds()
        );
    }
}