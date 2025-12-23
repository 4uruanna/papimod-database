<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\interface\IColumn;

class Column implements IColumn
{
    public function __construct(public readonly string $name) {}
}
