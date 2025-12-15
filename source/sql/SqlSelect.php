<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use PDOStatement;

final class SqlSelect
{

    private readonly SqlTable $table;
    private readonly ?array $columns;

    public function __construct(SqlTable $table, ?array $columns = null)
    {
        $this->table = $table;
        $this->columns = $columns;
    }

    public function fetch(): PDOStatement
    {
        $columns = $this->columns ? implode(', ', $this->columns) : '*';

        $query = "SELECT $columns FROM {$this->table->name}";

        $statement = Database::pdo()->prepare($query);

        return $statement;
    }
}
