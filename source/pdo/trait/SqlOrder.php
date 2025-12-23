<?php

namespace Papimod\Database\pdo\trait;

use Papimod\Database\pdo\enumerator\Order;

trait SqlOrder
{
    private string $order_query = "";

    public function orderBy(string $column, Order $order = Order::ASC): self
    {
        if ($order === Order::ASC || $order === Order::DESC) {
            if (strlen($this->order_query) === 0) {
                $this->order_query .= "ORDER BY ";
            } else {
                $this->order_query .= ", ";
            }

            $this->order_query .= "{$column} {$order->value}";
        }

        return $this;
    }
}
