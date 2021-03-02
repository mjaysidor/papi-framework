<?php
declare(strict_types=1);

namespace papi\Response;

class ErrorResponse extends JsonResponse
{
    public function __construct(
        string $errorMessage
    ) {
        parent::__construct(500, ['ERROR' => $errorMessage]);
    }
}