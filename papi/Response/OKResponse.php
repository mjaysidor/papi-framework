<?php
declare(strict_types=1);

namespace papi\Response;

class OKResponse extends JsonResponse
{
    public function __construct(
        ?array $responseData = null
    ) {
        parent::__construct(200, $responseData);
    }
}