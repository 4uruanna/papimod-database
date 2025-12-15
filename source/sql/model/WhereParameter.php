<?php

namespace Papimod\Database\sql\model;

use PDO;
use PDOStatement;

final class WhereParameter
{
    public const EQUAL = '=';
    public const GREATER = '>';
    public const LESS = '<';
    public const GREATER_EQUAL = '>=';
    public const LESS_EQUAL = '<=';
    public const NOT_EQUAL = '!=';
    public const LIKE = 'LIKE';
    public const NOT_LIKE = 'NOT LIKE';
    public const IN = 'IN';
    public const NOT_IN = 'NOT IN';
    public const IS_NULL = 'IS NULL';
    public const IS_NOT_NULL = 'IS NOT NULL';

    private static int $QUERY_INDEX = 0;

    private static function getQueryIndex(): int
    {
        self::$QUERY_INDEX++;
        return self::$QUERY_INDEX;
    }

    private readonly string $column;
    private readonly int $query_index;

    private string $operator;
    private mixed $value;
    private int $type = PDO::PARAM_STR;

    public function __construct(string $column)
    {
        $this->column = $column;
        $this->query_index = self::getQueryIndex();
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setOperator(string $operator): self
    {
        $this->operator = $operator;
        return $this;
    }

    public function __toString(): string
    {
        $query = "{$this->column} {$this->operator}";

        if ($this->operator !== WhereParameter::IS_NULL && $this->operator !== WhereParameter::IS_NOT_NULL) {
            if ($this->operator === WhereParameter::IN || $this->operator === WhereParameter::NOT_IN) {
                $c = count($this->value);
                $query .= " (";

                for ($i = 0; $i < $c; $i++) {
                    $query .= ":where_parameter_{$this->query_index}_{$i}";

                    if ($i < $c - 1) {
                        $query .= ", ";
                    }
                }

                $query .= ")";
            } else {
                $query .= " :where_parameter_{$this->query_index}";
            }
        }

        return $query;
    }

    public function bind(PDOStatement $statement): void
    {
        if ($this->operator !== WhereParameter::IS_NULL && $this->operator !== WhereParameter::IS_NOT_NULL) {
            if ($this->operator === WhereParameter::IN || $this->operator === WhereParameter::NOT_IN) {
                $ic = count($this->value);

                for ($i = 0; $i < $ic; $i++) {
                    $statement->bindValue(":where_parameter_{$this->query_index}_{$i}", $this->value[$i], $this->type);
                }
            } else {
                $statement->bindValue(":where_parameter_{$this->query_index}", $this->value, $this->type);
            }
        }
    }
}
