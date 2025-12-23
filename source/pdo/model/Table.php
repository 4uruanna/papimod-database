<?php

namespace Papimod\Database\query\model;

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
