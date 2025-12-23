<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\enumerator\Type;

class Bind
{
    public function __construct(
        public readonly string $key,
        public mixed $value,
        public Type $type = Type::STRING
    ) {}
}
