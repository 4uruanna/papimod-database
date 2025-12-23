<?php

namespace Papimod\Database\pdo\model;

class Table
{
    public function __construct(
        public readonly string $name,
        public readonly string $alias = ''
    ) {}

    public function __toString()
    {
        return "{$this->name} {$this->alias}";
    }
}
