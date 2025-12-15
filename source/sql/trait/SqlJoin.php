<?php

namespace Papimod\Database\sql\trait;

use Papimod\Database\sql\model\Join;

trait SqlJoin
{
    private ?Join $last_join = null;

    private string $join_query = "";

    public function innerJoin(string $table): self
    {
        $this->last_join = new Join(Join::INNER, $table);
        return $this;
    }

    public function leftJoin(string $table): self
    {
        $this->last_join = new Join(Join::LEFT, $table);
        return $this;
    }

    public function rightJoin(string $table): self
    {
        $this->last_join = new Join(Join::RIGHT, $table);
        return $this;
    }

    public function on(string $column_a, string $column_b): self
    {
        $this->last_join->setOn("$column_a = $column_b");
        $this->join_query .= " " . $this->last_join;
        return $this;
    }
}
