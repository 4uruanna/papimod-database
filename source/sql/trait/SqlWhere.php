<?php

namespace Papimod\Database\sql\trait;

use Papimod\Database\sql\model\WhereParameter;
use PDO;
use PDOStatement;

trait SqlWhere
{
    /**
     * @var WhereParameter[][]
     */
    private array $where_list = [];

    private WhereParameter $last_where_parameter;

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
        $this->where[] = [];
        return $this->and($column);
    }

    private function getWhereQuery(): string
    {
        $query = "";

        if (count($this->where_list) > 0) {
            $query = " WHERE "
                . implode(" OR ", array_map(
                    fn($parameter_list) => implode(
                        " AND ",
                        array_map(fn($parameter) => $parameter->__toString(), $parameter_list)
                    ),
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

    // Where Parameter

    public function isEqual(mixed $value, int $type = PDO::PARAM_STR)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::EQUAL)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isNotEqual(mixed $value, int $type = PDO::PARAM_STR)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::NOT_EQUAL)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isGreater(mixed $value, int $type = PDO::PARAM_INT)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::GREATER)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isLess(mixed $value, int $type = PDO::PARAM_INT)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::LESS)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isGreaterOrEqual(mixed $value, int $type = PDO::PARAM_INT)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::GREATER_EQUAL)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isLessOrEqual(mixed $value, int $type = PDO::PARAM_INT)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::LESS_EQUAL)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isLike(string $value)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::LIKE)
            ->setValue($value);
        return $this;
    }

    public function isNotLike(string $value)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::NOT_LIKE)
            ->setValue($value);
        return $this;
    }

    public function isIn(array $value, int $type = PDO::PARAM_STR)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::IN)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isNotIn(array $value, int $type = PDO::PARAM_STR)
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::NOT_IN)
            ->setValue($value)
            ->setType($type);
        return $this;
    }

    public function isNull()
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::IS_NULL)
            ->setType(PDO::PARAM_NULL);
        return $this;
    }

    public function isNotNull()
    {
        $this->last_where_parameter
            ->setOperator(WhereParameter::IS_NOT_NULL)
            ->setType(PDO::PARAM_NULL);
        return $this;
    }
}
