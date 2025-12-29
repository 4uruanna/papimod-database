<?php

namespace Papimod\Database\pdo;

use Papimod\Database\Database;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\model\Table;
use Papimod\Database\pdo\trait\SqlColumn;
use Papimod\Database\pdo\trait\SqlJoin;
use Papimod\Database\pdo\trait\SqlWhere;
use PDOStatement;

final class StatementUpdateBuilder
{
    use SqlWhere;
    use SqlJoin;
    use SqlColumn;

    public function __construct(
        public readonly Table $table,
        Column ...$column
    ) {
        $this->addColumn(...$column);
    }

    public function build(): PDOStatement
    {
        $pdo = Database::pdo();

        $statement = $pdo->prepare(
            <<<SQL
            UPDATE {$this->table}
            {$this->join_query}
            SET
            {$this->setQuery()}
            {$this->whereQuery()}
            SQL
        );

        $this->bindColumns($statement);
        $this->bindParameters($statement);
        return $statement;
    }

    private function setQuery(): string
    {
        return implode(', ', $this->columns);
    }
}
