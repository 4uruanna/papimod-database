<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\interface\IColumn;

class Column implements IColumn
{
    public function __construct(public readonly string $name) {}

    // public static function INT(string $name): 
    // {
    //     return new Column($name, Type::INTEGER);
    // }

    // public static function STRING(string $name): Column
    // {
    //     return new Column($name, Type::STRING);
    // }

    // public static function BOOL(string $name): Column
    // {
    //     return new Column($name, Type::BOOLEAN);
    // }
}
