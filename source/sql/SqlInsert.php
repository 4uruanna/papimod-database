<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use Papimod\Database\sql\model\Column;
use Papimod\Database\sql\trait\SqlValues;
use PDOStatement;

final class SqlInsert
{
    use SqlValues;

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
        $query = "INSERT INTO {$this->table->name} ("
            . implode(', ', array_map(fn(Column $column) => $column->name, $this->column_list))
            . ") VALUES "
            . $this->getInsertValuesQuery();

        $statement = Database::pdo()->prepare($query);
        $this->bindValues($statement);
        return $statement;
    }
}
