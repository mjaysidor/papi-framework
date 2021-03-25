<?php

declare(strict_types=1);

namespace papi\Response;

/**
 * Response returned when endpoint has been requested using invalid method
 */
class MethodNotAllowedResponse extends JsonResponse
{
    public function __construct(
        string $validMethod
    ) {
        parent::__construct(405, ['Method not allowed'], ['Allow' => $validMethod]);
    }
}
