<?php

declare(strict_types=1);

namespace papi\Controller;

use Closure;
use config\APIResponses;
use papi\Documentation\RouteParametersDocGenerator;
use papi\Worker\App;

/**
 * Controller handling resource & many to many relation endpoints
 */
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

    /**
     * Initializes API Responses Documentation
     */
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
            $apiResponses->getGETResponses($this->getGETResponseBodyDoc()),
            $apiResponses->getPOSTResponses(),
            $apiResponses->getDELETEResponses(),
            $apiResponses->getPUTResponses(),
            $this->getPOSTPUTBodyDoc(),
            $this->getQueryFiltersDoc(),
        ];
    }

    /**
     * Initializes URL and parameters
     */
    private function initUrl(): void
    {
        $urlParams = $this->getUrlIdParams();
        $this->urlIdParamsDoc = RouteParametersDocGenerator::generate($urlParams);
        $this->endpoint = $this->endpointWithId = "/$this->resourceName";

        foreach ($urlParams as $param) {
            $this->endpointWithId .= '/{'.$param.'}';
        }
    }

    /**
     * Add desired routes
     */
    abstract public function init(): void;

    /**
     * Get API resource on which the controller operates
     *
     * @return mixed
     */
    abstract protected function getResource(): mixed;

    /**
     * Get OpenAPI documentation of filters available for GET request
     *
     * @return array
     */
    abstract public function getQueryFiltersDoc(): array;

    /**
     * Get URL ID parameters used in SELECT requests
     *
     * @return array
     */
    abstract public function getUrlIdParams(): array;

    /**
     * Get OpenAPI documentation of POST/PUT requests body
     *
     * @return array
     */
    abstract public function getPOSTPUTBodyDoc(): array;

    /**
     * Get OpenAPI documentation of GET requests response body
     *
     * @return array
     */
    abstract public function getGETResponseBodyDoc(): array;

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
