<?php
declare(strict_types=1);

namespace framework\Callbacks;

interface PostExecutionHandler
{
    public function handle(array $data): ?string;
}