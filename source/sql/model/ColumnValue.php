<?php

namespace Papimod\Database\sql\model;

use PDO;
use PDOStatement;

final class ColumnValue
{
    private readonly int $query_index;
    public readonly string $column_name;
    public readonly int $pdo_type;
    public mixed $value;

    private static int $QUERY_INDEX = 0;

    private static function getQueryIndex(): int
    {
        self::$QUERY_INDEX++;
        return self::$QUERY_INDEX;
    }

    public function __construct(string $column_name, mixed $value, int $type)
    {
        $this->column_name = $column_name;
        $this->value = $value;
        $this->pdo_type = $value === null ? PDO::PARAM_NULL : $type;
        $this->query_index = self::getQueryIndex();
    }

    public function __toString()
    {
        return ":value_{$this->query_index}";
    }

    public function bind(PDOStatement $statement): void
    {
        $statement->bindValue(":value_{$this->query_index}", $this->value, $this->pdo_type);
    }
}
