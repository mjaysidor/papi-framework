<?php

declare(strict_types=1);

namespace papi\Callbacks;

interface PreExecutionBodyModifier
{
    /**
     * Modifies request body before executing it (ex. add created_at field containing current DateTime)
     */
    public function modify(array &$body): void;
}
