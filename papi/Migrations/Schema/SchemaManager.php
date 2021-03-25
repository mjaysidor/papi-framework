<?php

declare(strict_types=1);

namespace papi\Migrations\Schema;

use config\DatabaseConfig;
use papi\CLI\ConsoleOutput;
use papi\Config\ProjectStructure;
use papi\Resource\Field\Id;

/**
 * Handles database management operations, such as "create" & "drop"
 */
class SchemaManager
{
    public const MIGRATION_COLUMN_NAME = 'migrations_executed';

    private string $name;

    private string $user;

    private string $password;

    private string $host;

    private mixed $connection;

    public function __construct()
    {
        $this->name = DatabaseConfig::getName();
        $this->user = DatabaseConfig::getUsername();
        $this->password = DatabaseConfig::getPassword();
        $this->host = DatabaseConfig::getServer();
        $this->initConnection("host = $this->host dbname = postgres user = $this->user password = $this->password");
    }

    /**
     * Drop database
     */
    public function dropDb(): void
    {
        $this->query("DROP DATABASE $this->name;");
    }

    /**
     * Create database
     */
    public function createDb(): void
    {
        $this->query("create database $this->name owner $this->user;");
        $this->initConnection("host = $this->host dbname = $this->name user = $this->user password = $this->password");
        $this->createMigrationsTable();
    }

    /**
     * Creates db table containing executed migrations information
     */
    private function createMigrationsTable(): void
    {
        $migrationPathLength = strlen(ProjectStructure::getMigrationsPath()) + 35;
        $idDefinition = (new Id())->getProperties();
        $this->query(
            "create table ".self::MIGRATION_COLUMN_NAME
            ." (id $idDefinition, migration VARCHAR($migrationPathLength), current_state TEXT)"
        );
    }

    /**
     * Initializes database connection
     *
     * @param string $params
     */
    private function initConnection(string $params): void
    {
        if (empty($this->connection = pg_connect($params))) {
            ConsoleOutput::errorDie('database connection error: '.pg_last_error());
        }
    }

    /**
     * Executes a database query
     *
     * @param string $query
     */
    private function query(string $query): void
    {
        $result = pg_query($this->connection, $query);

        if ($result === false) {
            ConsoleOutput::errorDie(pg_last_error($this->connection));
        }
    }
}
