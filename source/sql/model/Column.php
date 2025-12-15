<?php

namespace Papimod\Database\sql\model;

use PDO;

final class Column
{
    public readonly string $name;
    public readonly ?int $pdo_type;

    public function __construct(string $name, ?int $pdo_type = null)
    {
        $this->name = $name;
        $this->pdo_type = $pdo_type;
    }

    public static function INT(string $name): Column
    {
        return new Column($name, PDO::PARAM_INT);
    }

    public static function STRING(string $name): Column
    {
        return new Column($name, PDO::PARAM_STR);
    }

    public static function FLOAT(string $name): Column
    {
        return new Column($name, PDO::PARAM_STR);
    }

    public static function BOOL(string $name): Column
    {
        return new Column($name, PDO::PARAM_BOOL);
    }
}
