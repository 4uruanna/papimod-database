<?php

namespace Papimod\Database;

use Papimod\Database\query\StatementTableBuilder;

final class StatementBuilder
{
    public static function from(string $table): StatementTableBuilder
    {
        return new StatementTableBuilder($table);
    }
}
