<?php

declare(strict_types=1);

namespace papi\Response;

/**
 * Response returned on successful request execution
 */
class OKResponse extends JsonResponse
{
    public function __construct(
        ?array $responseData = null
    ) {
        parent::__construct(200, $responseData);
    }
}
