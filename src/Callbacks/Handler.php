<?php
declare(strict_types=1);

namespace App\Callbacks;

class Handler implements \papi\Callbacks\PostExecutionHandler
{
    public function handle(array $data): ?string
    {
        return 'handled parapa';
    }
}