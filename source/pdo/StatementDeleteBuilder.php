<?php

namespace Papimod\Database\pdo;

use Papimod\Database\Database;
use Papimod\Database\pdo\model\Table;
use Papimod\Database\pdo\trait\SqlJoin;
use Papimod\Database\pdo\trait\SqlWhere;
use PDOStatement;

final class StatementDeleteBuilder
{
    use SqlWhere;
    use SqlJoin;

    public function __construct(public readonly Table $table)
    {
    }

    public function build(): PDOStatement
    {
        $pdo = Database::pdo();

        $statement = $pdo->prepare(
            <<<SQL
                DELETE
                FROM {$this->table}
                {$this->join_query}
                {$this->whereQuery()}
            SQL
        );

        $this->bindParameters($statement);
        return $statement;
    }
}
