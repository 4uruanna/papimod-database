<?php

namespace Papimod\Database\pdo\model;

class ForeignKey
{
    public function __construct(
        public readonly string $column_name,
        public readonly string $reference_table,
        public readonly string $reference_column
    ) {
    }

    public function __toString()
    {
        return <<<SQL
                FOREIGN KEY ({$this->column_name})
                REFERENCES {$this->reference_table}({$this->reference_column})
            SQL;
    }
}
