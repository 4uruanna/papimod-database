<?php

namespace Papimod\Database\sql;

use Papimod\Database\Database;
use Papimod\Database\sql\interface\IQuery;
use Papimod\Database\sql\model\Column;
use Papimod\Database\sql\model\ColumnValue;
use Papimod\Database\sql\trait\SqlValues;
use PDOStatement;

final class SqlInsert implements IQuery
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
        $columns = implode(', ', array_map(fn(Column $column) => $column->name, $this->column_list));
        $values = $this->getValuesQuery();

        $statement = Database::pdo()->prepare(
            <<<SQL
                INSERT INTO {$this->table->name}
                ( $columns )
                VALUES $values
            SQL
        );

        $this->bindColumnEntries($statement);
        return $statement;
    }


    public function value(object $value): self
    {
        $property_list = [];

        foreach ($this->column_list as $column) {
            if (property_exists($value, $column->name)) {
                $property_list[] = new ColumnValue(
                    $column->name,
                    $value->{$column->name},
                    $column->pdo_type
                );
            }
        }

        $this->column_value_list[] = $property_list;
        return $this;
    }

    public function getValuesQuery(): string
    {
        $query = [];

        foreach ($this->column_value_list as $value) {
            $query[] =  "( " . implode(", ", array_map(fn(ColumnValue $column_value) => $column_value, $value)) . " )";
        }

        return implode(", ", $query);
    }

    public function bindColumnEntries(PDOStatement $statement): void
    {
        foreach ($this->column_value_list as $value) {
            foreach ($value as $column_value) {
                $column_value->bind($statement);
            }
        }
    }
}
