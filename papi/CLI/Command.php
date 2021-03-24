<?php
declare(strict_types=1);

namespace papi\CLI;

interface Command
{
    public function getCommand(): string;
    public function execute(): void;
}
