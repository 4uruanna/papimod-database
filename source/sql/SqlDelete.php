<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use Papimod\Database\sql\interface\IQuery;
use Papimod\Database\sql\trait\SqlJoin;
use Papimod\Database\sql\trait\SqlWhere;
use PDOStatement;

final class SqlDelete implements IQuery
{
    use SqlJoin;
    use SqlWhere;

    private readonly SqlTable $table;

    public function __construct(SqlTable $table)
    {
        $this->table = $table;
    }

    public function build(): PDOStatement
    {
        $query = "DELETE {$this->table->name} FROM {$this->table->name}"
            . $this->getJoinQuery()
            . $this->getWhereQuery();

        $statement = Database::pdo()->prepare($query);
        $this->bindWhereParameters($statement);
        return $statement;
    }
}
