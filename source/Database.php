<?php

namespace Papimod\Database;

use PDO;

final class Database
{
    private static PDO $pdo;

    private static bool $transaction_lock = false;

    private static int $transaction_id = 0;

    public static function pdo(): PDO
    {
        if (isset(Database::$pdo) === false) {
            Database::$pdo = new PDO(
                "mysql:"
                    . "host=" . DATABASE_HOST . ";"
                    . "port=" . DATABASE_PORT . ";"
                    . "dbname=" . DATABASE_NAME  . ";"
                    . "charset=" .  DATABASE_CHARSET,
                DATABASE_USER,
                DATABASE_PASSWORD,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        }

        return Database::$pdo;
    }

    public static function beginTransaction(): int
    {
        if (Database::$transaction_lock === false) {
            Database::pdo()->beginTransaction();
            Database::$transaction_lock = true;
            Database::$transaction_id++;
            return Database::$transaction_id;
        }

        return 0;
    }

    public static function commit(int $id): bool
    {
        $is_committed = Database::$transaction_lock === true && Database::$transaction_id === $id;

        if ($is_committed) {
            Database::pdo()->commit();
            Database::$transaction_lock = false;
        }

        return $is_committed;
    }

    public static function rollback(int $id): bool
    {
        $is_rollbacked = Database::$transaction_lock === true && Database::$transaction_id === $id;

        if (Database::$transaction_lock === true && Database::$transaction_id === $id) {
            Database::pdo()->rollBack();
            Database::$transaction_lock = false;
        }

        return $is_rollbacked;
    }
}
