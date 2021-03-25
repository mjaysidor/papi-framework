<?php

declare(strict_types=1);

namespace papi\Response;

use Workerman\Protocols\Http\Response;

/**
 * Response containing JSON content
 */
class JsonResponse extends Response
{
    public function __construct(
        int $status = 200,
        ?array $body = null,
        array $headers = []
    ) {
        $headers['Content-Type'] = 'application/json';

        if ($body === null) {
            parent::__construct(
                $status,
                $headers
            );
        } else {
            parent::__construct(
                $status,
                $headers,
                json_encode($body, JSON_THROW_ON_ERROR)
            );
        }
    }
}
