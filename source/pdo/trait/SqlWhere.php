<?php

namespace Papimod\Database\pdo\trait;

use Papimod\Database\pdo\model\Parameter;
use Papimod\Database\pdo\enumerator\Operator;
use Papimod\Database\pdo\enumerator\Type;
use PDO;
use PDOStatement;

trait SqlWhere
{
    private array $parameters = [];
    private Parameter $last_parameter;
    private array $last_parameter_group;

    private function whereQuery(): string
    {
        $query = "";

        if (count($this->parameters) > 0) {
            $query = "WHERE "
                . implode(" OR ", array_map(
                    fn($parameter_list) => implode(" AND ", $parameter_list),
                    $this->parameters
                ));
        }

        return $query;
    }

    private function bindParameters(PDOStatement $statement): void
    {
        $group_count = count($this->parameters);
        for ($group_index = 0; $group_index < $group_count; $group_index++) {
            $parameter_count = count($this->parameters[$group_index]);
            for ($parameter_index = 0; $parameter_index < $parameter_count; $parameter_index++) {
                $this->parameters[$group_index][$parameter_index]->bind($statement);
            }
        }
    }

    # Initializers (where, and, or)

    public function where(string $column): self
    {
        if (count($this->parameters) === 0) {
            $this->last_parameter_group = [];
            $this->parameters[] = $this->last_parameter_group;
        }

        return $this->and($column);
    }

    public function and(string $column): self
    {
        $this->last_parameter = new Parameter($column);
        return $this;
    }

    public function or(string $column): self
    {
        $this->last_parameter_group = [];
        $this->parameters[] = $this->last_parameter_group;
        return $this->and($column);
    }

    # Operators

    public function isEqual(mixed $value, Type $type = Type::STRING)
    {
        $this->last_parameter->operator = Operator::IS_EQUAL;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = $type;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isNotEqual(mixed $value, Type $type = Type::STRING)
    {
        $this->last_parameter->operator = Operator::IS_NOT_EQUAL;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = $type;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isGreater(mixed $value, Type $type = Type::STRING)
    {
        $this->last_parameter->operator = Operator::IS_GREATER;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = $type;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isLess(mixed $value, Type $type = Type::STRING)
    {
        $this->last_parameter->operator = Operator::IS_LESS;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = $type;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isGreaterOrEqual(mixed $value, Type $type = Type::STRING)
    {
        $this->last_parameter->operator = Operator::IS_GREATER_OR_EQUAL;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = $type;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isLessOrEqual(mixed $value, Type $type = Type::STRING)
    {
        $this->last_parameter->operator = Operator::IS_LESS_OR_EQUAL;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = $type;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isLike(string $value)
    {
        $this->last_parameter->operator = Operator::IS_LIKE;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = Type::STRING;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isNotLike(string $value)
    {
        $this->last_parameter->operator = Operator::IS_NOT_LIKE;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = Type::STRING;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isIn(array $value, Type $type = Type::STRING)
    {
        $this->last_parameter->operator = Operator::IS_IN;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = $type;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isNotIn(array $value, Type $type = Type::STRING)
    {
        $this->last_parameter->operator = Operator::IS_NOT_IN;
        $this->last_parameter->value = $value;
        $this->last_parameter->type = $type;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isNull()
    {
        $this->last_parameter->operator = Operator::IS_NULL;
        $this->last_parameter->type = Type::NULL;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }

    public function isNotNull()
    {
        $this->last_parameter->operator = Operator::IS_NOT_NULL;
        $this->last_parameter->type = Type::NULL;
        $this->last_parameter_group[] = $this->last_parameter;
        return $this;
    }
}
