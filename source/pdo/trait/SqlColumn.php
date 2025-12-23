<?php

namespace Papimod\Database\pdo\trait;

use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\model\InsertColumn;
use Papimod\Database\pdo\model\UpdateColumn;
use PDOStatement;

trait SqlColumn
{
    /** @var Column|UpdateColumn|InsertColumn[] */
    private array $columns = [];

    private function addColumn(Column|UpdateColumn|InsertColumn|string ...$column)
    {
        foreach ($column as $c) {
            if (is_string($c)) {
                $this->columns[] = new Column($c);
            } else {
                $this->columns[] = $c;
            }
        }
    }

    public function columnQuery(): string
    {
        $result = '*';

        if (count($this->columns)) {
            $result = implode(', ', array_map(fn($c) => $c->name, $this->columns));
        }

        return $result;
    }

    public function bindColumns(PDOStatement $statement): void
    {
        foreach ($this->columns as $column) {
            if ($column instanceof UpdateColumn) {
                $column->bind($statement);
            }
        }
    }
}
