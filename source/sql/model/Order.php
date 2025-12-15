<?php

namespace Papimod\Database\sql\model;

final class Order
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';

    private readonly string $column;
    private readonly string $order;

    public function __construct(string $column, string $order)
    {
        $this->column = $column;
        $this->order = $order;
    }

    public function __toString(): string
    {
        return $this->column . ' ' . $this->order;
    }
}
