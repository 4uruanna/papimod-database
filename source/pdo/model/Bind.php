<?php

namespace Papimod\Database\pdo\model;

use DateTime;
use Papimod\Database\pdo\enumerator\PdoType;
use Papimod\Database\pdo\enumerator\Type;
use Papimod\Date\DateService;
use PDOStatement;

class Bind
{
    private static int $uid = 0;
    private static DateService $date_service;

    protected static function generateUniqueKey(): string
    {
        self::$uid++;
        return ":__bind_" . self::$uid;
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
        if ($this->value instanceof DateTime) {
            if (isset(self::$date_service) === false) {
                self::$date_service = new DateService();
            }

            switch ($this->type) {
                case Type::DATE:
                    $this->value = self::$date_service->formatDate($this->value);
                    break;

                case Type::DATETIME:
                    $this->value = self::$date_service->format($this->value);
                    break;

                case Type::TIME:
                    $this->value = self::$date_service->formatTime($this->value);
                    break;
            }
        }

        $statement->bindValue(
            $this->key,
            $this->value,
            PdoType::$values[$this->type->value]
        );
    }
}
