<?php /** @noinspection ClassMethodNameMatchesFieldNameInspection */
declare(strict_types=1);

namespace papi\Worker;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use papi\Response\ErrorResponse;
use papi\Response\NotFoundResponse;
use Throwable;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;
use function FastRoute\simpleDispatcher;

class App extends Worker
{
    protected array $routeInfo = [];

    private array $callbacksCache = [];

    protected Dispatcher $dispatcher;

    public function __construct($socket_name = '', array $context_option = [])
    {
        parent::__construct($socket_name, $context_option);
        $this->onMessage = [$this, 'onMessage'];
    }

    public function getRouteInfo(): array
    {
        return $this->routeInfo;
    }

    public function addDocumentedRoute(
        string $method,
        string $path,
        callable $callback,
        array $requestBody = [],
        array $urlParameters = [],
        array $responses = [],
        ?string $resourceName = null
    ): void {
        $this->routeInfo[$method][] = [
            $path,
            $callback,
            'resourceName' => $resourceName,
            'responses'    => $responses,
            'body'         => $requestBody,
            'parameters'   => $urlParameters,
        ];
    }

    public function addRoute(
        string $method,
        string $path,
        callable $callback
    ): void {
        $methods = (array)$method;
        foreach ($methods as $m) {
            $this->routeInfo[$m][] = [$path, $callback];
        }
    }

    public function start(): void
    {
        $this->dispatcher = simpleDispatcher(
            function (RouteCollector $r) {
                foreach ($this->routeInfo as $method => $endpoints) {
                    foreach ($endpoints as $data) {
                        $r->addRoute($method, $data[0], $data[1]);
                    }
                }
            }
        );

        Worker::runAll();
    }

    public function onMessage(TcpConnection $connection, Request $request): void
    {
        try {
            $method = $request->method();

            $callback = $this->callbacksCache[$request->path().$method] ?? null;
            if ($callback) {
                $connection->send($callback($request));

                return;
            }

            $ret = $this->dispatcher->dispatch($method, $request->path());
            if ($ret[0] === Dispatcher::FOUND) {
                $callback = $ret[1];
                if (! empty($ret[2])) {
                    $args = array_values($ret[2]);
                    $callback = static function ($request) use ($args, $callback) {
                        return $callback($request, ... $args);
                    };
                }
                $this->callbacksCache[$request->path().$method] = $callback;
                $connection->send($callback($request));

                return;
            }
        } catch (Throwable $e) {
            $connection->send(new ErrorResponse($e->getMessage()));
        }

        $connection->send(new NotFoundResponse());
    }
}