<?php

declare(strict_types=1);

namespace papi\Worker;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use papi\Response\JsonResponse;
use papi\Response\MethodNotAllowedResponse;
use papi\Response\NotFoundResponse;
use papi\Utils\ErrorLogger;
use Throwable;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;
use function FastRoute\simpleDispatcher;

class App extends Worker
{
    /**
     * @var Route[]
     */
    protected array $routes = [];

    /**
     * @var callable[]
     */
    private array $handlerCache = [];

    protected Dispatcher $dispatcher;

    public function __construct(
        string $protocolAddress = 'http://0.0.0.0:3000',
        array $context = []
    ) {
        parent::__construct($protocolAddress, $context);
        $this->onMessage = [$this, 'onRequest'];
        $this->count = 4;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function addRoute(
        mixed $method,
        string $path,
        callable $callback,
        array $requestBody = [],
        array $urlParameters = [],
        array $responses = [],
        string $resourceName = 'Other'
    ): void {
        $methods = (array)$method;
        foreach ($methods as $m) {
            $this->routes[]
                = new Route(
                $path,
                $m,
                $callback,
                $resourceName,
                $responses,
                $requestBody,
                $urlParameters
            );
        }
    }

    public function start(): void
    {
        $this->dispatcher = simpleDispatcher(
            function (RouteCollector $router) {
                foreach ($this->routes as $route) {
                    $router->addRoute($route->getMethod(), $route->getPath(), $route->getHandler());
                }
            }
        );

        Worker::runAll();
    }

    public function onRequest(
        TcpConnection $connection,
        Request $request
    ): void {
        try {
            $method = $request->method();

            if ($handler = $this->handlerCache[$request->path().$method] ?? null) {
                $connection->send($handler($request));

                return;
            }

            $route = $this->dispatcher->dispatch($method, $request->path());
            $status = $route[0];

            if ($status === Dispatcher::FOUND) {
                [, $handler, $argumentList] = $route;
                if (! empty($argumentList)) {
                    $args = array_values($argumentList);
                    $handler = static function ($request) use ($args, $handler) {
                        return $handler($request, ... $args);
                    };
                }
                $this->handlerCache[$request->path().$method] = $handler;
                $connection->send($handler($request));

                return;
            }

            if ($status === Dispatcher::METHOD_NOT_ALLOWED) {
                $connection->send(new MethodNotAllowedResponse(implode(',', $route[1])));

                return;
            }

            if ($status === Dispatcher::NOT_FOUND) {
                $connection->send(new NotFoundResponse());

                return;
            }
        } catch (Throwable $e) {
            ErrorLogger::logError($e);
            $connection->send(
                new JsonResponse(500, ['error:' => $e->getMessage(), '@' => $e->getFile().': '.$e->getLine()])
            );

            return;
        }
    }
}
