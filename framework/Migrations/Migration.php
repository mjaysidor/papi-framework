<?php
declare(strict_types=1);

namespace framework\Migrations;

use framework\CLI\ConsoleOutput;
use framework\Database\MedooHandler;
use Medoo\Medoo;

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
        ConsoleOutput::output('Migration executed: '.get_class($this));
    }

    abstract protected function migrate(): void;
}