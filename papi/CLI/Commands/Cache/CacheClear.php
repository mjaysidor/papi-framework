<?php

declare(strict_types=1);

namespace papi\CLI\Commands\Cache;

use papi\CLI\Command;
use papi\CLI\ConsoleOutput;

/**
 * Clears database query result cache
 */
class CacheClear implements Command
{
    public function getCommand(): string
    {
        return 'cache:clear';
    }

    public function getDescription(): string
    {
        return 'Clears database query result cache';
    }

    public function execute(): void
    {
        $cache = glob('var/cache/*.tmp');

        if ($cache === false) {
            ConsoleOutput::errorDie('Cannot get cache');
        }

        if (empty($cache)) {
            ConsoleOutput::warning('No cache detected');
            die();
        }

        array_map('unlink', $cache);

        ConsoleOutput::success('Cache cleared!');
    }
}
