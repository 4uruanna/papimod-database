<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\enumerator\Type;
use PDOStatement;

class Bind
{
    public function __construct(
        public readonly string $key,
        public mixed $value = null,
        public Type $type = Type::STRING
    ) {}


    public function bind(PDOStatement $statement): void
    {
        $statement->bindValue(
            $this->key,
            $this->value,
            $this->type->value
        );
    }
}
