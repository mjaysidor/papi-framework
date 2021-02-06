<?php
declare(strict_types=1);

namespace papi\Response;

class MethodNotAllowedResponse extends JsonResponse
{
    public function __construct(
        string $validMethod
    ) {
        parent::__construct(405, ['Method not allowed'], ['Allow' => $validMethod]);
    }
}