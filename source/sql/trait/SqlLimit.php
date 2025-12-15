<?php

namespace Papimod\Database\sql\trait;

trait SqlLimit
{
    private int $limit = 0;
    private int $offset = 0;

    public function limit(int $limit, int $offset = 0): self
    {
        if ($limit > 0) {
            $this->limit = $limit;
        }

        if ($offset > 0) {
            $this->offset = $offset;
        }

        return $this;
    }

    private function getLimitQuery(): string
    {
        $query = "";

        if ($this->limit) {
            $query .= " LIMIT {$this->limit} ";

            if ($this->offset) {
                $query .= " OFFSET {$this->offset} ";
            }
        }

        return $query;
    }
}
