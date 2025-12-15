<?php

namespace Papimod\Database\sql;

final class SqlQuery
{
    public static function from(string $table): SqlTable
    {
        return new SqlTable($table);
    }
}
