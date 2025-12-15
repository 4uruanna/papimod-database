<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use PDOStatement;

final class SqlTable
{
    public readonly string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function select(?array $columns = null): SqlSelect
    {
        return new SqlSelect($this, $columns);
    }

    public function drop(): PDOStatement
    {
        return Database::pdo()->prepare("DROP TABLE {$this->name}");
    }

    public function truncate(): PDOStatement
    {
        return Database::pdo()->prepare("TRUNCATE TABLE {$this->name}");
    }
}
