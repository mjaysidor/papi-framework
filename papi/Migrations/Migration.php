<?php
declare(strict_types=1);

namespace papi\Migrations;

use Medoo\Medoo;
use papi\CLI\ConsoleOutput;
use papi\Database\MedooHandler;

abstract class Migration
{
    protected ?Medoo $handler = null;

    public function __construct()
    {
        try {
            $this->handler = MedooHandler::getDbHandler();
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