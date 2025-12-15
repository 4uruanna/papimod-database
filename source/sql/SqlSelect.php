<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use Papimod\Database\sql\trait\SqlLimit;
use Papimod\Database\sql\trait\SqlOffset;
use PDOStatement;

final class SqlSelect
{
    use SqlLimit;

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

        $query = "SELECT $columns FROM {$this->table->name}"
            . $this->getLimitQuery();

        $statement = Database::pdo()->prepare($query);
        return $statement;
    }
}
