<?php
declare(strict_types=1);

namespace papi\Response;

class ValidationErrorResponse extends JsonResponse
{
    public function __construct(
        string $errorMessage
    ) {
        parent::__construct(400, ['ERROR' => $errorMessage]);
    }
}