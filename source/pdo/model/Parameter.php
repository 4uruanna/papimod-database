<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\enumerator\Operator;
use Papimod\Database\pdo\enumerator\PdoType;
use Papimod\Database\pdo\enumerator\Type;
use PDOStatement;

final class Parameter extends Bind
{
    public readonly string $column;

    public Operator $operator;

    public function __construct(string $column)
    {
        parent::__construct(null, Type::TEXT);
        $this->column = $column;
    }

    public function __toString(): string
    {
        $query = "`{$this->column}` {$this->operator->value} ";

        if ($this->operator !== Operator::IS_NULL && $this->operator !== Operator::IS_NOT_NULL) {
            if ($this->operator === Operator::IS_IN || $this->operator === Operator::IS_NOT_IN) {
                $c = count($this->value);
                $query .= " (";

                for ($i = 0; $i < $c; $i++) {
                    $query .= $this->key . "_$i";

                    if ($i < $c - 1) {
                        $query .= ", ";
                    }
                }

                $query .= ")";
            } else {
                $query .= $this->key;
            }
        }

        return $query;
    }

    public function bind(PDOStatement $statement): void
    {
        if ($this->operator !== Operator::IS_NULL && $this->operator !== Operator::IS_NOT_NULL) {
            if ($this->operator === Operator::IS_IN || $this->operator === Operator::IS_NOT_IN) {
                $ic = count($this->value);

                for ($i = 0; $i < $ic; $i++) {
                    $statement->bindValue(
                        $this->key . "_$i",
                        $this->value[$i],
                        PdoType::$values[$this->type->value]
                    );
                }
            } else {
                $statement->bindValue(
                    $this->key,
                    $this->value,
                    PdoType::$values[$this->type->value]
                );
            }
        }
    }
}
