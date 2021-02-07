<?php
declare(strict_types=1);

namespace papi\Database;

use config\DatabaseConfig;

class PostgresDb
{
    public $connection;

    private int $aliasCount = 0;

    private array $aliasValues = [];

    public static function getConnection()
    {
        $isLocal = DatabaseConfig::isLocal();
        $name = DatabaseConfig::getName();
        $user = DatabaseConfig::getUsername();
        $password = DatabaseConfig::getPassword();

        if ($isLocal) {
            return pg_connect("dbname = $name user = $user password = $password");
        }

        $host = DatabaseConfig::getServer();

        return pg_connect("host = $host dbname = $name user = $user password = $password");
    }

    public function __construct()
    {
        $this->connection = self::getConnection();
        set_error_handler(
            function ($number, $text) {
                if (str_contains($text, 'Query failed')) {
                    pg_close($this->connection);
                }
            }
        );
    }

    public function getError(): string
    {
        return pg_last_error($this->connection);
    }

    public function createTable(
        string $table,
        array $fields
    ): bool {
        $query = "CREATE TABLE $table (";
        $lastKey = array_key_last($fields);
        foreach ($fields as $name => $column) {
            $query .= "$name $column";
            if ($lastKey !== $name) {
                $query .= ', ';
            }
        }
        $query .= ');';

        return pg_query($this->connection, $query);
    }

    public function select(
        string $table,
        ?array $columns = null,
        ?array $filters = null,
        ?string $orderBy = null,
        ?string $order = null
    ): array|string {
        if ($columns) {
            $query = 'SELECT '.implode(',', $columns)." FROM $table";
        } else {
            $query = "SELECT * FROM $table";
        }

        if ($filters) {
            $this->addFilters($query, $filters);
        }
        if ($orderBy) {
            if ($order && $order !== 'DESC') {
                $order = 'ASC';
            }
            $query .= ' order by '.pg_escape_string($orderBy)." $order";
        }

        $queryParams = pg_query_params($this->connection, $query, $this->aliasValues);

        if (! $queryParams) {
            return $this->getError();
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
            return $this->getError();
        }

        return pg_affected_rows($queryParams);
    }

    public function insert(
        string $table,
        array $data
    ): array|string {
        $query = "INSERT INTO $table ";
        $query .= '('.implode(', ', array_keys($data)).')';
        $query .= ' VALUES(';
        foreach ($data as $key => $condition) {
            if (array_key_first($data) !== $key) {
                $query .= ', ';
            }
            $this->addAlias($query, $condition);
        }
        $query .= ') RETURNING *';
        $queryParams = pg_query_params($this->connection, $query, $this->aliasValues);

        if (! $queryParams) {
            return $this->getError();
        }

        return pg_fetch_assoc($queryParams);
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
            $query .= pg_escape_string($key).'=';
            $this->addAlias($query, $condition);
        }
        $this->addWhereConditions($query, $where);
        $queryParams = pg_query_params($this->connection, $query, $this->aliasValues);

        if (! $queryParams) {
            return $this->getError();
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

    private function addFilters(string &$query, array $filters): void
    {
        $query .= ' WHERE ';
        $firstKey = array_key_first($filters);
        foreach ($filters as $key => $condition) {
            if ($firstKey !== $key) {
                $query .= ' AND ';
            }
            $query .= pg_escape_string($key).'=';
            $this->addAlias($query, $condition);
        }
    }
}