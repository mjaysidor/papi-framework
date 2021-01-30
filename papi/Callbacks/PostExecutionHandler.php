<?php
declare(strict_types=1);

namespace papi\Callbacks;

interface PostExecutionHandler
{
    public function handle(array $data): ?string;
}