<?php

namespace Papimod\Database\pdo\model;

class PrimaryKey
{
    public function __construct(public readonly string $column_name)
    {
    }

    public function __toString()
    {
        return <<<SQL
                PRIMARY KEY ({$this->column_name})
            SQL;
    }
}
