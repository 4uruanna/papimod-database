<?php

namespace Papimod\Database\pdo;

use Papimod\Database\Database;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\model\Table;
use Papimod\Database\pdo\trait\SqlColumn;
use PDOStatement;

final class StatementInsertBuilder
{
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
            INSERT INTO {$this->table}
            ( {$this->columnQuery()} )
            VALUES 
            ( {$this->valueQuery()} )
            SQL
        );

        $this->bindColumns($statement);
        return $statement;
    }

    public function valueQuery(): string
    {
        return implode(', ', $this->columns);
    }
}
