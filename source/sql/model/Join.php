<?php

namespace Papimod\Database\sql\model;

final class Join
{
    public const INNER = 'INNER';
    public const LEFT = 'LEFT';
    public const RIGHT = 'RIGHT';
    public const FULL = 'FULL OUTER';

    private readonly string $table;
    private readonly string $type;
    private ?string $on;

    public function __construct(string $type, string $table)
    {
        $this->type = $type;
        $this->table = $table;
    }

    public function setOn(string $on): void
    {
        $this->on = $on;
    }

    public function __toString()
    {
        return "{$this->type} JOIN {$this->table} ON {$this->on}";
    }
}
