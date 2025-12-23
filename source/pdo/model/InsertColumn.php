<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\enumerator\Type;
use Papimod\Database\pdo\interface\IColumn;

class InsertColumn extends Bind implements IColumn
{
    public static function INT(string $name, int $value): InsertColumn
    {
        return new InsertColumn($name, $value, Type::INTEGER);
    }

    public static function STRING(string $name, string $value): InsertColumn
    {
        return new InsertColumn($name, $value, Type::STRING);
    }

    public static function BOOL(string $name, bool $value): InsertColumn
    {
        return new InsertColumn($name, $value, Type::BOOLEAN);
    }

    public function __construct(
        public readonly string $name,
        mixed $value,
        Type $type
    ) {
        return parent::__construct(
            self::generateUniqueKey(),
            $value,
            $type
        );
    }

    public function __toString()
    {
        return "$this->key";
    }
}
