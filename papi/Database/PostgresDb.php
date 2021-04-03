<?php

declare(strict_types=1);

namespace papi\Database;

use config\DatabaseConfig;
use papi\Utils\CacheStorage;
use RuntimeException;

/**
 * Handles basic queries to Postgresql database
 */
class PostgresDb
{
    private mixed $connection;

    private int $aliasCount = 0;

    private array $aliasValues = [];

    /**
     * Returns connection to database specified in DatabaseConfig
     *
     * @return mixed
     */
    public static function getConnection(): mixed
    {
        $isLocal = DatabaseConfig::isLocal();
        $name = DatabaseConfig::getName();
        $user = DatabaseConfig::getUsername();
        $password = DatabaseConfig::getPassword();

        if ($isLocal === true) {
            return pg_connect("dbname = $name user = $user password = $password");
        }

        $host = DatabaseConfig::getServer();

        return pg_connect("host = $host dbname = $name user = $user password = $password");
    }

    public function __construct()
    {
        $this->connection = self::getConnection();
    }

    /**
     * Executes provided query
     *
     * @param string $sql
     *
     * @return array
     */
    public function query(
        string $sql
    ): array {
        if (($result = pg_query($this->connection, $sql)) === false) {
            throw  $this->throwError();
        }

        return pg_fetch_all($result);
    }

    /**
     * Returns last db connection error
     *
     * @return RuntimeException
     */
    private function throwError(): RuntimeException
    {
        return new RuntimeException(pg_last_error($this->connection));
    }

    public function clearAliases(): void
    {
        $this->aliasValues = [];
        $this->aliasCount = 0;
    }

    /**
     * Checks for existence of a db table element
     *
     * @param string $table
     * @param array  $filters
     *
     * @return bool
     */
    public function exists(
        string $table,
        array $filters = [],
    ): bool {
        $query = "select exists(select 1 from $table";

        if ($filters !== []) {
            $this->addWhereConditions($query, $filters);
        }
        $query .= ')';

        if (($queryParams = pg_query_params($this->connection, $query, $this->aliasValues)) === false) {
            throw $this->throwError();
        }
        if (($result = pg_fetch_row($queryParams)) === false) {
            throw $this->throwError();
        }

        return $result[0] === 't';
    }

    /**
     * SQL query SELECT
     *
     * @param string      $from
     * @param array       $columns
     * @param array       $filters
     * @param string|null $orderBy
     * @param string|null $order
     * @param int|null    $limit
     * @param string|null $offset
     * @param bool        $cache
     * @param int|null    $cacheTtl
     *
     * @return array
     */
    public function select(
        string $from,
        array $columns = [],
        array $filters = [],
        ?string $orderBy = null,
        ?string $order = null,
        ?int $limit = null,
        ?string $offset = null,
        bool $cache = false,
        ?int $cacheTtl = 300
    ): array {
        if ($columns !== []) {
            $query = 'select '.implode(',', $columns)." from $from";
        } else {
            $query = "select * from $from";
        }
        if ($filters !== []) {
            $this->addQueryFilters($query, $filters);
        }
        if ($orderBy !== null) {
            if ($order !== 'desc') {
                $order = 'asc';
            }
            $query .= ' order by '.pg_escape_string($orderBy)." $order";
        }
        if ($limit !== null) {
            $query .= " limit $limit";
        }
        if ($offset !== null) {
            $query .= " offset $offset";
        }

        if ($cache === true) {
            $params = $this->aliasValues;
            $escapedQuery = preg_replace_callback(
                '/\$(\d+)\b/',
                static function ($match) use ($params) {
                    $key = ($match[1] - 1);

                    return (is_null($params[$key]) ? 'NULL' : pg_escape_literal($params[$key]));
                },
                $query
            );

            if (($cachedResult = CacheStorage::get($escapedQuery)) !== false) {
                return $cachedResult;
            }

            if (($queryParams = pg_query_params($this->connection, $query, $this->aliasValues)) === false) {
                throw $this->throwError();
            }

            $queryResult = pg_fetch_all($queryParams);
            CacheStorage::set($escapedQuery, $queryResult, $cacheTtl);

            return $queryResult;
        }

        if (($queryParams = pg_query_params($this->connection, $query, $this->aliasValues)) === false) {
            throw $this->throwError();
        }

        return pg_fetch_all($queryParams);
    }

    /**
     * SQL query DELETE
     *
     * @param string $table
     * @param array  $where
     *
     * @return int
     */
    public function delete(
        string $table,
        array $where = []
    ): int {
        $query = "delete from $table";
        $this->addWhereConditions($query, $where);

        if (($queryParams = pg_query_params($this->connection, $query, $this->aliasValues)) === false) {
            throw $this->throwError();
        }

        return pg_affected_rows($queryParams);
    }

    /**
     * Start transaction query (use if executing multiple POST/PUT/DELETE operations in a row)
     */
    public function beginTransaction(): void
    {
        pg_query($this->connection, 'begin');
    }

    /**
     * Commit (execute) started transaction
     */
    public function executeTransaction(): void
    {
        pg_query($this->connection, 'commit');
    }

    /**
     * SQL query INSERT
     *
     * @param string $table
     * @param array  $data
     *
     * @return array
     */
    public function insert(
        string $table,
        array $data
    ): array {
        $query = "insert into $table (";
        $firstKey = array_key_first($data);

        foreach ($data as $key => $condition) {
            if ($firstKey !== $key) {
                $query .= ', ';
            }
            if (! is_string($key)) {
                throw new RuntimeException('Array keys must be of type string');
            }
            $query .= pg_escape_string($key);
        }
        $query .= ") values(";

        foreach ($data as $key => $condition) {
            if ($firstKey !== $key) {
                $query .= ', ';
            }
            $this->addAlias($query, $condition);
        }
        $query .= ') returning *';

        if (($queryParams = pg_query_params($this->connection, $query, $this->aliasValues)) === false) {
            throw $this->throwError();
        }

        $result = pg_fetch_assoc($queryParams);

        if ($result === false) {
            throw $this->throwError();
        }

        $this->clearAliases();

        return $result;
    }

    /**
     * SQL query UPDATE
     *
     * @param string $table
     * @param array  $data
     * @param array  $where
     *
     * @return int
     */
    public function update(
        string $table,
        array $data,
        array $where
    ): int {
        $query = "update $table set ";
        $firstKey = array_key_first($data);

        foreach ($data as $key => $condition) {
            if (! is_string($key)) {
                throw new RuntimeException('Array keys must be of type string');
            }
            if ($firstKey !== $key) {
                $query .= ',';
            }
            $query .= pg_escape_string($key).'=';
            $this->addAlias($query, $condition);
        }
        $this->addWhereConditions($query, $where);

        if (($queryParams = pg_query_params($this->connection, $query, $this->aliasValues)) === false) {
            throw $this->throwError();
        }

        $this->clearAliases();

        return pg_affected_rows($queryParams);
    }

    /**
     * Adds value as query alias
     *
     * @param string $query
     * @param mixed  $value
     */
    private function addAlias(
        string &$query,
        mixed $value
    ): void {
        $query .= ' $'.++$this->aliasCount;
        $this->aliasValues[] = $value;
    }

    /**
     * Adds where conditions to query
     *
     * @param string $query
     * @param array  $where
     */
    private function addWhereConditions(
        string &$query,
        array $where
    ): void {
        $query .= ' where ';
        $firstKey = array_key_first($where);

        foreach ($where as $key => $condition) {
            if (! is_string($key)) {
                throw new RuntimeException('Array keys must be of type string');
            }
            if ($firstKey !== $key) {
                $query .= ' and ';
            }
            $query .= pg_escape_string($key);
            $this->addAlias($query, $condition);
        }
    }

    /**
     * Adds filters to SELECT query (parameter equal/larger than/less than value etc.)
     *
     * @param string $query
     * @param array  $filters
     */
    private function addQueryFilters(
        string &$query,
        array $filters
    ): void {
        $query .= ' where ';
        $firstKey = array_key_first($filters);

        foreach ($filters as $key => $condition) {
            if (! is_string($key)) {
                throw new RuntimeException('Array keys must be of type string');
            }
            if ($firstKey !== $key) {
                $query .= ' and ';
            }

            if (is_array($condition)) {
                foreach ($condition as $operator => $value) {
                    $condition = $value;

                    if ($operator === 'lte') {
                        $key .= '<=';
                        continue;
                    }
                    if ($operator === 'gte') {
                        $key .= '>=';
                        continue;
                    }
                    if ($operator === 'lt') {
                        $key .= '<';
                        continue;
                    }
                    if ($operator === 'gt') {
                        $key .= '>';
                        continue;
                    }
                }
            } else {
                $key .= '=';
            }
            $query .= pg_escape_string($key);
            $this->addAlias($query, $condition);
        }
    }
}
