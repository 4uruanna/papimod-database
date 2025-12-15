<?php

namespace Papimod\Database\sql\trait;

use Papimod\Database\sql\model\Join;

trait SqlJoin
{
    /**
     * @var Join[]
     */
    private array $join_list = [];

    public function innerJoin(string $table): self
    {
        $this->join_list[] = new Join(Join::INNER, $table);
        return $this;
    }

    public function leftJoin(string $table): self
    {
        $this->join_list[] = new Join(Join::LEFT, $table);
        return $this;
    }

    public function rightJoin(string $table): self
    {
        $this->join_list[] = new Join(Join::RIGHT, $table);
        return $this;
    }

    public function on(string $column_a, string $column_b): self
    {
        end($this->join_list)->setOn("$column_a = $column_b");
        return $this;
    }

    private function getJoinQuery(): string
    {
        return " " . implode(" ", $this->join_list) . " ";
    }
}
