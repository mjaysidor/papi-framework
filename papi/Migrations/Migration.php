<?php
declare(strict_types=1);

namespace papi\Migrations;

use papi\CLI\ConsoleOutput;
use papi\Database\PostgresDb;

abstract class Migration
{
    protected $handler;

    public function __construct()
    {
        try {
            $this->handler = new PostgresDb();
        } catch (\PDOException $exception) {
            dump($exception->getMessage());
        }
    }

    public function execute(): void
    {
        $this->migrate();
        ConsoleOutput::info('Migration executed: '.get_class($this));
    }

    abstract protected function migrate(): void;
}