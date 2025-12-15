<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use Papimod\Database\sql\interface\IQuery;
use PDOStatement;

final class SqlTruncate implements IQuery
{
    private readonly SqlTable $table;

    public function __construct(SqlTable $table)
    {
        $this->table = $table;
    }

    public function build(): PDOStatement
    {
        return Database::pdo()
            ->prepare("TRUNCATE TABLE {$this->table->name}");
    }
}
