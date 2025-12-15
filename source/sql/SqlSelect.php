<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use Papimod\Database\sql\interface\IQuery;
use Papimod\Database\sql\trait\SqlJoin;
use Papimod\Database\sql\trait\SqlLimit;
use Papimod\Database\sql\trait\SqlOrderBy;
use Papimod\Database\sql\trait\SqlWhere;
use PDOStatement;

final class SqlSelect implements IQuery
{
    use SqlLimit;
    use SqlOrderBy;
    use SqlJoin;
    use SqlWhere;

    private readonly SqlTable $table;
    private readonly ?array $columns;

    public function __construct(SqlTable $table, ?array $columns = null)
    {
        $this->table = $table;
        $this->columns = $columns;
    }

    public function build(): PDOStatement
    {
        $where = $this->getWhereQuery();
        $order = $this->getOrderByQuery();
        $columns = $this->columns
            ? implode(', ', $this->columns)
            : '*';

        $statement = Database::pdo()->prepare(
            <<<SQL
                SELECT $columns FROM {$this->table->name}
                {$this->join_query}
                {$this->limit_query}
                $where
                $order
            SQL
        );

        $this->bindWhereParameters($statement);
        return $statement;
    }
}
