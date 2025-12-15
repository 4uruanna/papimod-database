<?php

namespace Papimod\Database\sql\trait;

use Papimod\Database\sql\enumerator\Operator;
use Papimod\Database\sql\enumerator\Type;
use Papimod\Database\sql\model\WhereParameter;
use PDO;
use PDOStatement;

trait SqlWhere
{
    private array $where_list = [];
    private WhereParameter $last_where_parameter;

    private function getWhereQuery(): string
    {
        $query = "";

        if (count($this->where_list) > 0) {
            $query = "WHERE "
                . implode(" OR ", array_map(
                    fn($parameter_list) => implode(" AND ", $parameter_list),
                    $this->where_list
                ));
        }

        return $query;
    }

    private function bindWhereParameters(PDOStatement $statement): void
    {
        $group_count = count($this->where_list);
        for ($group_index = 0; $group_index < $group_count; $group_index++) {
            $parameter_count = count($this->where_list[$group_index]);
            for ($parameter_index = 0; $parameter_index < $parameter_count; $parameter_index++) {
                $this->where_list[$group_index][$parameter_index]->bind($statement);
            }
        }
    }

    # Initializers (where, and, or)

    public function where(string $column): self
    {
        if (count($this->where_list) === 0) {
            $this->where_list[] = [];
        }

        return $this->and($column);
    }

    public function and(string $column): self
    {
        $last_index = count($this->where_list) - 1;
        $this->last_where_parameter = new WhereParameter($column);
        $this->where_list[$last_index][] = $this->last_where_parameter;
        return $this;
    }

    public function or(string $column): self
    {
        $this->where_list[] = [];
        return $this->and($column);
    }

    # Operators

    public function isEqual(mixed $value, int $type = Type::STRING)
    {
        $this->last_where_parameter
            ->setOperator(Operator::EQUAL)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isNotEqual(mixed $value, int $type = Type::STRING)
    {
        $this->last_where_parameter
            ->setOperator(Operator::NOT_EQUAL)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isGreater(mixed $value, int $type = Type::STRING)
    {
        $this->last_where_parameter
            ->setOperator(Operator::GREATER)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isLess(mixed $value, int $type = Type::STRING)
    {
        $this->last_where_parameter
            ->setOperator(Operator::LESS)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isGreaterOrEqual(mixed $value, int $type = Type::STRING)
    {
        $this->last_where_parameter
            ->setOperator(Operator::GREATER_EQUAL)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isLessOrEqual(mixed $value, int $type = Type::STRING)
    {
        $this->last_where_parameter
            ->setOperator(Operator::LESS_EQUAL)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isLike(string $value)
    {
        $this->last_where_parameter
            ->setOperator(Operator::LIKE)
            ->setValue($value);
        return $this;
    }

    public function isNotLike(string $value)
    {
        $this->last_where_parameter
            ->setOperator(Operator::NOT_LIKE)
            ->setValue($value);
        return $this;
    }

    public function isIn(array $value, int $type = Type::STRING)
    {
        $this->last_where_parameter
            ->setOperator(Operator::IN)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isNotIn(array $value, int $type = Type::STRING)
    {
        $this->last_where_parameter
            ->setOperator(Operator::NOT_IN)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isNull()
    {
        $this->last_where_parameter
            ->setOperator(Operator::IS_NULL)
            ->setType(PDO::PARAM_NULL);
        return $this;
    }

    public function isNotNull()
    {
        $this->last_where_parameter
            ->setOperator(Operator::IS_NOT_NULL)
            ->setType(PDO::PARAM_NULL);
        return $this;
    }
}
