<?php

namespace Papimod\Database;

use Papimod\Database\pdo\StatementTableBuilder;

final class StatementBuilder
{
    public static function from(string $table): StatementTableBuilder
    {
        return new StatementTableBuilder($table);
    }
}
