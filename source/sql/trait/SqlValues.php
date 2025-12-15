<?php

namespace Papimod\Database\sql\trait;

use Papimod\Database\sql\model\ColumnValue;
use PDOStatement;

trait SqlValues
{
    /**
     * @var ColumnValue[][]
     */
    private array $value_list = [];

    public function value(object $value): self
    {
        $property_list = [];

        foreach ($this->column_list as $column) {
            if (property_exists($value,  $column->name)) {
                $property_list[] = new ColumnValue(
                    $column->name,
                    $value->{$column->name},
                    $column->pdo_type
                );
            }
        }

        $this->value_list[] = $property_list;
        return $this;
    }

    public function getInsertValuesQuery(): string
    {
        $query = [];

        foreach ($this->value_list as $value) {
            $query[] =  "(" . implode(", ", array_map(fn(ColumnValue $column_value) => $column_value, $value)) . ")";
        }

        return implode(", ", $query);
    }

    public function getUpdateValuesQuery(): string
    {
        $query = [];

        foreach ($this->value_list as $value) {
            $query[] =  implode(
                ", ",
                array_map(
                    fn(ColumnValue $column_value) => "{$column_value->column_name} = {$column_value}",
                    $value
                )
            );
        }

        return implode(", ", $query);
    }

    public function bindValues(PDOStatement $statement): void
    {
        foreach ($this->value_list as $value) {
            foreach ($value as $column_value) {
                $column_value->bind($statement);
            }
        }
    }
}
