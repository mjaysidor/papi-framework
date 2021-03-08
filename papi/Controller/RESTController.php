<?php
declare(strict_types=1);

namespace papi\Controller;

use Closure;
use config\APIResponses;
use papi\Documentation\OpenApiParamConverter;
use papi\Worker\App;

abstract class RESTController
{
    protected App $api;

    protected APIResponses $apiResponses;

    public string $resourceName;

    public function __construct(App $api)
    {
        $this->api = $api;
        $this->apiResponses = new APIResponses();
    }

    abstract public function init(): void;

    abstract public function getEndpoint(): string;

    abstract public function getQueryFilters(): array;

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
        $this->api->addRoute(
            'POST',
            $this->getEndpoint(),
            $callback,
            $this->getPOSTPUTBody(),
            [],
            $this->apiResponses->getPOSTResponses(),
            $this->resourceName
        );
    }

    protected function getById(Closure $callback): void
    {
        $this->api->addRoute(
            'GET',
            $this->getEndpointWithId(),
            $callback,
            [],
            OpenApiParamConverter::convertArrayToDoc($this->getEndpointIds()),
            $this->apiResponses->getGETResponses($this->getGETResponseBody()),
            $this->resourceName
        );
    }

    protected function get(Closure $callback): void
    {
        $this->api->addRoute(
            'GET',
            $this->getEndpoint(),
            $callback,
            [],
            $this->getQueryFilters(),
            $this->apiResponses->getGETResponses($this->getGETResponseBody()),
            $this->resourceName
        );
    }

    protected function delete(Closure $callback): void
    {
        $this->api->addRoute(
            'DELETE',
            $this->getEndpointWithId(),
            $callback,
            [],
            OpenApiParamConverter::convertArrayToDoc($this->getEndpointIds()),
            $this->apiResponses->getDELETEResponses(),
            $this->resourceName
        );
    }

    protected function put(Closure $callback): void
    {
        $this->api->addRoute(
            'PUT',
            $this->getEndpointWithId(),
            $callback,
            $this->getPOSTPUTBody(),
            OpenApiParamConverter::convertArrayToDoc($this->getEndpointIds()),
            $this->apiResponses->getPUTResponses(),
            $this->resourceName
        );
    }
}