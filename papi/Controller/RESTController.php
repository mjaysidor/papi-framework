<?php
declare(strict_types=1);

namespace papi\Controller;

use Closure;
use config\APIResponses;
use papi\Documentation\RouteParametersDocGenerator;
use papi\Worker\App;

abstract class RESTController
{
    protected App $api;

    public string $resourceName;

    protected string $endpoint;

    protected string $endpointWithId;

    protected array $urlIdParamsDoc = [];

    protected array $postPutBody = [];

    protected array $getResponses = [];

    protected array $postResponses = [];

    protected array $deleteResponses = [];

    protected array $putResponses = [];

    protected array $queryFilters = [];

    public function __construct(App $api)
    {
        $this->api = $api;
        $this->initDoc();
        $this->initUrl();
    }

    private function initDoc(): void
    {
        $apiResponses = new APIResponses();
        [
            $this->getResponses,
            $this->postResponses,
            $this->deleteResponses,
            $this->putResponses,
            $this->postPutBody,
            $this->queryFilters,
        ]
            = [
            $apiResponses->getGETResponses($this->getGETResponseBody()),
            $apiResponses->getPOSTResponses(),
            $apiResponses->getDELETEResponses(),
            $apiResponses->getPUTResponses(),
            $this->getPOSTPUTBody(),
            $this->getQueryFilters(),
        ];
    }

    private function initUrl(): void
    {
        $urlParams = $this->getUrlIdParams();
        $this->urlIdParamsDoc = RouteParametersDocGenerator::generate($urlParams);
        $this->endpoint = $this->endpointWithId = "/$this->resourceName";

        foreach ($urlParams as $param) {
            $this->endpointWithId .= '/{'.$param.'}';
        }
    }

    abstract public function init(): void;

    abstract protected function getResource(): mixed;

    abstract public function getQueryFilters(): array;

    abstract public function getUrlIdParams(): array;

    abstract public function getPOSTPUTBody(): array;

    abstract public function getGETResponseBody(): array;

    protected function post(Closure $callback): void
    {
        $this->api->addRoute(
            'POST',
            $this->endpoint,
            $callback,
            $this->postPutBody,
            [],
            $this->postResponses,
            $this->resourceName
        );
    }

    protected function put(Closure $callback): void
    {
        $this->api->addRoute(
            'PUT',
            $this->endpointWithId,
            $callback,
            $this->postPutBody,
            $this->urlIdParamsDoc,
            $this->putResponses,
            $this->resourceName
        );
    }

    protected function getById(Closure $callback): void
    {
        $this->api->addRoute(
            'GET',
            $this->endpointWithId,
            $callback,
            [],
            $this->urlIdParamsDoc,
            $this->getResponses,
            $this->resourceName
        );
    }

    protected function get(Closure $callback): void
    {
        $this->api->addRoute(
            'GET',
            $this->endpoint,
            $callback,
            [],
            $this->queryFilters,
            $this->getResponses,
            $this->resourceName
        );
    }

    protected function delete(Closure $callback): void
    {
        $this->api->addRoute(
            'DELETE',
            $this->endpointWithId,
            $callback,
            [],
            $this->urlIdParamsDoc,
            $this->deleteResponses,
            $this->resourceName
        );
    }
}
