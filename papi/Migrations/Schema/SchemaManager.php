<?php
declare(strict_types=1);

namespace papi\Migrations\Schema;

use config\DatabaseConfig;
use papi\CLI\ConsoleOutput;
use papi\Config\ProjectStructure;
use papi\Resource\Field\Id;
use RuntimeException;

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
        $this->getPostgresConnection();
    }

    public function dropDb(): void
    {
        $this->query("DROP DATABASE $this->name;");
    }

    public function createDb(): void
    {
        $this->query("create database $this->name owner $this->user;");
        $this->getDbConnection();
        $this->createMigrationsTable();
    }

    private function createMigrationsTable(): void
    {
        $migrationPathLength = strlen(ProjectStructure::getMigrationsPath()) + 35;
        $idDefinition = (new Id())->getProperties();
        $this->query(
            "create table ".self::MIGRATION_COLUMN_NAME
            ." (id $idDefinition, migration VARCHAR($migrationPathLength), current_state TEXT)"
        );
    }

    private function getPostgresConnection(): void
    {
        $this->connection = pg_connect(
            "host = $this->host dbname = postgres user = $this->user password = $this->password"
        );
        if (empty($this->connection)) {
            throw new RuntimeException('database connection error: '.pg_last_error());
        }
    }

    private function getDbConnection(): void
    {
        $this->connection = pg_connect(
            "host = $this->host dbname = $this->name user = $this->user password = $this->password"
        );
        if (empty($this->connection)) {
            throw new RuntimeException('database connection error: '.pg_last_error());
        }
    }

    private function query(string $query): void
    {
        $result = pg_query($this->connection, $query);

        if ($result === false) {
            ConsoleOutput::errorDie(pg_last_error($this->connection));
        }
    }
}