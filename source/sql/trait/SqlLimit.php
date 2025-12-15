<?php

namespace Papimod\Database\sql\trait;

trait SqlLimit
{
    private string $limit_query = "";

    public function limit(int $limit, int $offset = 0): self
    {
        $limit = $limit > 0 ? $limit : PHP_INT_MAX;
        $offset = $offset > 0 ? $offset : 0;

        if ($limit > 0 || $offset > 0) {
            $this->limit_query = " LIMIT $limit OFFSET $offset ";
        }

        return $this;
    }
}
