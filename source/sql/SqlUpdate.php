<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use Papimod\Database\sql\model\Column;
use Papimod\Database\sql\trait\SqlJoin;
use Papimod\Database\sql\trait\SqlValues;
use Papimod\Database\sql\trait\SqlWhere;
use PDOStatement;

final class SqlUpdate
{
    use SqlValues;
    use SqlWhere;
    use SqlJoin;

    /**
     * @param Column[]|null $column_list
     */
    private readonly ?array $column_list;
    private readonly SqlTable $table;

    public function __construct(SqlTable $table, Column ...$column_list)
    {
        $this->table = $table;
        $this->column_list = $column_list;
    }

    public function build(): PDOStatement
    {
        $query = "UPDATE {$this->table->name} "
            . $this->getJoinQuery()
            . "SET " . $this->getUpdateValuesQuery()
            . $this->getWhereQuery();

        $statement = Database::pdo()->prepare($query);
        $this->bindValues($statement);
        $this->bindWhereParameters($statement);
        return $statement;
    }
}
