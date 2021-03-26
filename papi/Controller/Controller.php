<?php

declare(strict_types=1);

namespace papi\Controller;

use Closure;
use papi\Worker\App;

/**
 * Handles server route management
 */
abstract class Controller
{
    protected App $api;

    public function __construct(App $api)
    {
        $this->api = $api;
    }

    abstract public function init(): void;

    /**
     * Adds POST route
     *
     * @param string  $endpoint
     * @param Closure $callback
     */
    protected function post(
        string $endpoint,
        Closure $callback
    ): void {
        $this->api->addRoute(
            'POST',
            $endpoint,
            $callback,
        );
    }

    /**
     * Adds GET route
     *
     * @param string  $endpoint
     * @param Closure $callback
     */
    protected function get(
        string $endpoint,
        Closure $callback
    ): void {
        $this->api->addRoute(
            'GET',
            $endpoint,
            $callback,
        );
    }

    /**
     * Adds DELETE route
     *
     * @param string  $endpoint
     * @param Closure $callback
     */
    protected function delete(
        string $endpoint,
        Closure $callback
    ): void {
        $this->api->addRoute(
            'DELETE',
            $endpoint,
            $callback,
        );
    }

    /**
     * Adds PUT route
     *
     * @param string  $endpoint
     * @param Closure $callback
     */
    protected function put(
        string $endpoint,
        Closure $callback
    ): void {
        $this->api->addRoute(
            'PUT',
            $endpoint,
            $callback,
        );
    }
}
