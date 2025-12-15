<?php

namespace Papimod\Database\sql\model;

use Papimod\Database\sql\enumerator\Operator;
use Papimod\Database\sql\enumerator\Type;
use PDOStatement;

final class WhereParameter
{
    private const PREFIX = ":where_parameter_";

    private static int $UID = 0;
    private static function getUID(): int
    {
        return self::$UID++;
    }

    private readonly string $column;
    private readonly int $query_index;

    private string $operator;
    private mixed $value;
    private int $type = Type::STRING;

    public function __construct(string $column)
    {
        $this->column = $column;
        $this->query_index = self::getUID();
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
        $query = "{$this->column} {$this->operator} ";

        if ($this->operator !== Operator::IS_NULL && $this->operator !== Operator::IS_NOT_NULL) {
            if ($this->operator === Operator::IN || $this->operator === Operator::NOT_IN) {
                $c = count($this->value);
                $query .= " (";

                for ($i = 0; $i < $c; $i++) {
                    $query .= self::PREFIX . $this->query_index . "_$i";

                    if ($i < $c - 1) {
                        $query .= ", ";
                    }
                }

                $query .= ")";
            } else {
                $query .= self::PREFIX . $this->query_index;
            }
        }

        return $query;
    }

    public function bind(PDOStatement $statement): void
    {
        if ($this->operator !== Operator::IS_NULL && $this->operator !== Operator::IS_NOT_NULL) {
            if ($this->operator === Operator::IN || $this->operator === Operator::NOT_IN) {
                $ic = count($this->value);

                for ($i = 0; $i < $ic; $i++) {
                    $statement->bindValue(
                        self::PREFIX . $this->query_index . "_$i",
                        $this->value[$i],
                        $this->type
                    );
                }
            } else {
                $statement->bindValue(
                    self::PREFIX . $this->query_index,
                    $this->value,
                    $this->type
                );
            }
        }
    }
}
