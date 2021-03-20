<?php

declare(strict_types=1);

namespace papi\Response;

class AccessDeniedResponse extends JsonResponse
{
    public function __construct(
        ?string $errorMessage = null
    ) {
        parent::__construct(403, ["Access denied. $errorMessage"]);
    }
}
