<?php
declare(strict_types=1);

namespace papi\Callbacks;

interface PreExecutionBodyModifier
{
    public function modify(array &$data): void;
}