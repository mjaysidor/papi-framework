<?php
declare(strict_types=1);

namespace papi\Database;

use config\DatabaseConfig;

class PostgresDb
{
    public $connection;

    public int $aliasCount = 0;

    public array $aliasValues = [];

    public function __construct()
    {
        $isLocal = DatabaseConfig::isLocal();
        $name = DatabaseConfig::getName();
        $user = DatabaseConfig::getUsername();
        $password = DatabaseConfig::getPassword();

        if ($isLocal) {
            $this->connection = pg_pconnect("dbname = $name user = $user password = $password");
        } else {
            $host = DatabaseConfig::getServer();
            $this->connection = pg_pconnect("host = $host dbname = $name user = $user password = $password");
        }
    }

    public function select(
        string $table,
        ?array $where = null,
        ?array $columns = null,
        ?string $orderBy = null,
        ?string $order = null
    ): array {
        if ($columns) {
            $query = 'SELECT '.implode(',', $columns)." FROM $table";
        } else {
            $query = "SELECT * FROM $table";
        }

        if ($where) {
            $this->addWhereConditions($query, $where);
        }
        if ($orderBy) {
            if ($order && $order !== 'DESC') {
                $order = 'ASC';
            }
            $query .= ' order by '.pg_escape_string($orderBy)." $order";
        }
        $queryParams = pg_query_params($this->connection, $query, $this->aliasValues);

        if (! $queryParams) {
            return [pg_last_error($this->connection)];
        }

        return pg_fetch_all($queryParams);
    }

    public function delete(
        string $table,
        array $where = []
    ): int|string {
        $query = "DELETE FROM $table";
        $this->addWhereConditions($query, $where);
        $queryParams = pg_query_params($this->connection, $query, $this->aliasValues);

        if (! $queryParams) {
            return pg_last_error($this->connection);
        }

        return pg_affected_rows($queryParams);
    }

    public function insert(
        string $table,
        array $data
    ): int|string {
        $query = "INSERT INTO $table ";
        $query .= '('.implode(', ', array_keys($data)).')';
        $query .= ' VALUES(';
        foreach ($data as $key => $condition) {
            if (array_key_first($data) !== $key) {
                $query .= ', ';
            }
            $this->addAlias($query, $condition);
        }
        $query .= ')';

        $queryParams = pg_query_params($this->connection, $query, $this->aliasValues);

        if (! $queryParams) {
            return pg_last_error($this->connection);
        }

        return pg_affected_rows($queryParams);
    }

    public function update(
        string $table,
        array $data,
        array $where
    ): int|string {
        $query = "UPDATE $table SET ";
        $firstKey = array_key_first($data);

        foreach ($data as $key => $condition) {
            if ($firstKey !== $key) {
                $query .= ',';
            }
            $query .= pg_escape_string($key);
            $this->addAlias($query, $condition);
        }
        $this->addWhereConditions($query, $where);
        $queryParams = pg_query_params($this->connection, $query, $this->aliasValues);

        if (! $queryParams) {
            return pg_last_error($this->connection);
        }

        return pg_affected_rows($queryParams);
    }

    private function addAlias(string &$query, $value): void
    {
        $query .= ' $'.++$this->aliasCount;
        $this->aliasValues[] = $value;
    }

    private function addWhereConditions(string &$query, array $where): void
    {
        $query .= ' WHERE ';
        $firstKey = array_key_first($where);
        foreach ($where as $key => $condition) {
            if ($firstKey !== $key) {
                $query .= ' AND ';
            }
            $query .= pg_escape_string($key);
            $this->addAlias($query, $condition);
        }
    }
}