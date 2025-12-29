<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\enumerator\Type;

class Column extends Bind
{
    public function __construct(
        public string $name,
        public mixed $value = null,
        public Type $type = Type::TEXT,
        public bool $nullable = false,
        public bool $unsigned = false,
        public bool $auto_increment = false,
        public string $default = ""
    ) {
        parent::__construct($value, $type);
    }
}
