<?php
declare(strict_types=1);

namespace papi\Response;

use Workerman\Protocols\Http\Response;

class JsonResponse extends Response

{
    public function __construct(
        int $status = 200,
        array $body = [],
        array $headers = []
    ) {
        parent::__construct($status, array_merge($headers, ['Content-Type' => 'application/json']), json_encode($body));
    }
}