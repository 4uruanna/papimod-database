<?php

namespace Papimod\Database\sql\trait;

use Papimod\Database\sql\enumerator\Order;
use Papimod\Database\sql\model\OrderEntry;

trait SqlOrderBy
{
    private array $order_list = [];

    public function orderBy(string $column, string $order = Order::ASC): self
    {
        if ($order === Order::ASC || $order === Order::DESC) {
            $this->order_list[] = new OrderEntry($column, $order);
        }

        return $this;
    }

    private function getOrderByQuery(): string
    {
        $query = "";
        $order_count = count($this->order_list);

        if ($order_count) {
            $query .= "ORDER BY ";

            for ($index = 0; $index < $order_count; $index++) {
                $query .= $this->order_list[$index];

                if ($index < $order_count - 1) {
                    $query .= ", ";
                }
            }
        }

        return $query;
    }
}
