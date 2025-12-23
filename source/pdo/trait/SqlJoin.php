<?php

namespace Papimod\Database\pdo\trait;

use Papimod\Database\pdo\enumerator\Join;

trait SqlJoin
{
    private string $join_query = "";

    public function innerJoin(string $table, string $column_a, string $column_b): self
    {
        $this->join(Join::INNER, $table, $column_a, $column_b);
        return $this;
    }

    public function leftJoin(string $table, string $column_a, string $column_b): self
    {
        $this->join(Join::LEFT, $table, $column_a, $column_b);
        return $this;
    }

    public function rightJoin(string $table, string $column_a, string $column_b): self
    {
        $this->join(Join::RIGHT, $table, $column_a, $column_b);
        return $this;
    }

    public function fullJoin(string $table, string $column_a, string $column_b): self
    {
        $this->join(Join::FULL, $table, $column_a, $column_b);
        return $this;
    }

    public function join(Join $type, string $table, string $column_a, string $column_b)
    {
        $query = " " . $type->value . " JOIN {$table} ON {$column_a} = {$column_b}";

        if (Join::FULL === $type) {
            $query = <<<SQL
            LEFT JOIN $table ON $column_a = $column_b
            UNION
            SELECT * FROM {$this->table->name}
            RIGHT JOIN {$table}  ON {$column_a} = {$column_b}
            SQL;
        }

        $this->join_query .= $query;
    }
}
