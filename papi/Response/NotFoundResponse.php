<?php

declare(strict_types=1);

namespace papi\Response;

/**
 * Response returned when requested content has not been found
 */
class NotFoundResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(404, ['Not found']);
    }
}
