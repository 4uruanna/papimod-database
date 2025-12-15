<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use Papimod\Database\sql\interface\IQuery;
use Papimod\Database\sql\model\Column;
use Papimod\Database\sql\model\ColumnValue;
use Papimod\Database\sql\trait\SqlJoin;
use Papimod\Database\sql\trait\SqlValues;
use Papimod\Database\sql\trait\SqlWhere;
use PDOStatement;

final class SqlUpdate implements IQuery
{
    use SqlWhere;
    use SqlJoin;
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
        $where = $this->getWhereQuery();
        $values = implode(
            ", ",
            array_map(
                fn(ColumnValue $column_value) => "{$column_value->column_name} = {$column_value}",
                $this->column_value_list
            )
        );

        $statement = Database::pdo()->prepare(
            <<<SQL
                UPDATE {$this->table->name}
                {$this->join_query}
                SET $values
                $where
            SQL
        );

        foreach ($this->column_value_list as $column_value) {
            $column_value->bind($statement);
        }

        $this->bindWhereParameters($statement);
        return $statement;
    }

    public function set(object $value): self
    {
        $this->column_value_list = [];

        foreach ($this->column_list as $column) {
            if (property_exists($value, $column->name)) {
                $this->column_value_list[] = new ColumnValue(
                    $column->name,
                    $value->{$column->name},
                    $column->pdo_type
                );
            }
        }

        return $this;
    }
}
