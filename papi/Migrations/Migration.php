<?php

declare(strict_types=1);

namespace papi\Migrations;

/**
 * Contains migration data - SQL to execute & current state of db schema
 */
interface Migration
{
    /**
     * Returns SQL to be executed
     *
     * @return array
     */
    public function getSQL(): array;

    /**
     * Returns current database schema (mapping)
     *
     * @return array
     */
    public function getMapping(): array;
}
