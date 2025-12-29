<?php

namespace Papimod\Database\pdo;

use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\model\Table;

final class StatementTableBuilder
{
    public Table $table;

    public function __construct(string $name, string $alias = '')
    {
        $this->table = new Table($name, $alias);
    }

    public function create(Column ...$column): StatementCreateBuilder
    {
        return new StatementCreateBuilder($this->table, ...$column);
    }

    public function delete(): StatementDeleteBuilder
    {
        return new StatementDeleteBuilder($this->table);
    }

    public function drop(): StatementDropBuilder
    {
        return new StatementDropBuilder($this->table);
    }

    public function insert(Column ...$column): StatementInsertBuilder
    {
        return new StatementInsertBuilder($this->table, ...$column);
    }

    public function select(Column|string ...$column): StatementSelectBuilder
    {
        return new StatementSelectBuilder($this->table, ...$column);
    }

    public function truncate(): StatementTruncateBuilder
    {
        return new StatementTruncateBuilder($this->table);
    }

    public function update(Column ...$column): StatementUpdateBuilder
    {
        return new StatementUpdateBuilder($this->table, ...$column);
    }
}
