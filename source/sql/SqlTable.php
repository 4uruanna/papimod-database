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

    public function delete(): SqlDelete
    {
        return new SqlDelete($this);
    }

    public function drop(): PDOStatement
    {
        return Database::pdo()->prepare("DROP TABLE {$this->name}");
    }

    public function insert(): SqlInsert
    {
        return new SqlInsert($this);
    }

    public function select(?array $columns = null): SqlSelect
    {
        return new SqlSelect($this, $columns);
    }

    public function truncate(): PDOStatement
    {
        return Database::pdo()->prepare("TRUNCATE TABLE {$this->name}");
    }
}
