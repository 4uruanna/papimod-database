<?php

namespace Papimod\Database\query;

use Papimod\Database\query\model\Table;

final class StatementTableBuilder
{
    public Table $table;

    public function __construct(string $name, string $alias = '')
    {
        $this->table = new Table($name, $alias);
    }

    public function drop(): StatementDropBuilder
    {
        return new StatementDropBuilder($this->table);
    }

    public function truncate(): StatementTruncateBuilder
    {
        return new StatementTruncateBuilder($this->table);
    }
}
