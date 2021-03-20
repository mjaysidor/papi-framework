<?php

declare(strict_types=1);

namespace papi\Migrations;

interface Migration
{
    public function getSQL(): array;

    public function getMapping(): array;
}
