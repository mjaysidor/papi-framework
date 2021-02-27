<?php
declare(strict_types=1);

namespace papi\Controller;

use Closure;
use papi\Worker\App;

abstract class Controller
{
    protected App $api;

    public function __construct(App $api)
    {
        $this->api = $api;
    }

    abstract public function init(): void;

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