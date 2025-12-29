<?php

namespace Papimod\Database\pdo\model;

use Papimod\Database\pdo\enumerator\Type;
use PDOStatement;

class Bind
{
    private static int $uid = 0;

    protected static function generateUniqueKey(): string
    {
        self::$uid++;
        return ":__b" . self::$uid;
    }

    public readonly string $key;

    public function __construct(
        public mixed $value,
        public Type $type
    ) {
        $this->key = Bind::generateUniqueKey();
    }


    public function bind(PDOStatement $statement): void
    {
        $statement->bindValue(
            $this->key,
            $this->value,
            $this->type->value
        );
    }
}
