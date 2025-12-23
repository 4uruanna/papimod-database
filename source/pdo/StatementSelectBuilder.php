<?php

namespace Papimod\Database\pdo;

use Papimod\Database\Database;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\model\Table;
use Papimod\Database\pdo\trait\SqlColumn;
use Papimod\Database\pdo\trait\SqlJoin;
use Papimod\Database\pdo\trait\SqlLimit;
use Papimod\Database\pdo\trait\SqlOrder;
use Papimod\Database\pdo\trait\SqlWhere;
use PDOStatement;

final class StatementSelectBuilder
{
    use SqlColumn;
    use SqlJoin;
    use SqlLimit;
    use SqlOrder;
    use SqlWhere;

    public function __construct(public readonly Table $table, Column|string ...$column)
    {
        $this->addColumn(...$column);
    }

    public function build(): PDOStatement
    {
        $pdo = Database::pdo();

        $statement = $pdo->prepare(
            <<<SQL
                SELECT {$this->columnQuery()}
                FROM {$this->table}
                {$this->join_query}
                {$this->limit_query}
                {$this->whereQuery()}
                {$this->order_query}
            SQL
        );

        $this->bindParameters($statement);
        return $statement;
    }
}
