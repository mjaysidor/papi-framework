<?php
declare(strict_types=1);

namespace framework\Callbacks;

interface PreExecutionBodyModifier
{
    public function modify(array &$data): void;
}