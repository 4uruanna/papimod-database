<?php

namespace Papimod\Database\pdo\trait;

use Papimod\Database\pdo\interface\IColumn;
use Papimod\Database\pdo\model\Column;

trait SqlColumn
{
    /** @var IColumn[] */
    private array $columns = [];

    private function addColumn(Column|string ...$column)
    {
        foreach ($column as $c) {
            if (is_string($column)) {
                $this->columns[] = new Column($column);
            } else {
                $this->columns[] = $column;
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
}
