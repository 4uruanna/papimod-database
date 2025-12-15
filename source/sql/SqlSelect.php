<?php

namespace Papimod\Database\sql;

final class SqlSelect
{
    private readonly SqlTable $table;
    private readonly ?array $columns;

    public function __construct(SqlTable $table, ?array $columns = null)
    {
        $this->table = $table;
        $this->columns = $columns;
    }
}
