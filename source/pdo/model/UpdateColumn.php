<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\enumerator\Type;
use Papimod\Database\pdo\interface\IColumn;

class UpdateColumn extends Bind implements IColumn
{
    public static function INT(string $name, int $value): UpdateColumn
    {
        return new UpdateColumn($name, $value, Type::INTEGER);
    }

    public static function STRING(string $name, string $value): UpdateColumn
    {
        return new UpdateColumn($name, $value, Type::STRING);
    }

    public static function BOOL(string $name, bool $value): UpdateColumn
    {
        return new UpdateColumn($name, $value, Type::BOOLEAN);
    }

    private static int $uid = 0;

    private static function generateUniqueKey(): int
    {
        self::$uid++;
        return ":update_" . self::$uid;
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
        return "{$this->name} = {$this->key}";
    }
}
