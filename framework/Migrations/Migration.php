<?php
declare(strict_types=1);

namespace framework\Migrations;

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

    abstract public function migrate(): void;
}