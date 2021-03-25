<?php
declare(strict_types=1);

namespace papi\CLI;

/**
 * CLI executable command (ex. cache clear)
 */
interface Command
{
    /**
     * Get CLI input which triggers execution
     *
     * @return string
     */
    public function getCommand(): string;

    /**
     * Get a brief description of what the command does
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Execute desired actions
     */
    public function execute(): void;
}
