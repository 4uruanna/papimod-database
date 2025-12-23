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
                    . "host=" . PAPI_DATABASE_HOST . ";"
                    . "port=" . PAPI_DATABASE_PORT . ";"
                    . "dbname=" . PAPI_DATABASE_NAME  . ";"
                    . "charset=" .  PAPI_DATABASE_CHARSET,
                PAPI_DATABASE_USER,
                PAPI_DATABASE_PASSWORD,
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
            Database::$pdo->commit();
            Database::$transaction_lock = false;
            Database::$transaction_id++;
        }

        return $is_committed;
    }

    public static function rollback(int $id): bool
    {
        $is_rollbacked = Database::$transaction_lock === true && Database::$transaction_id === $id;

        if (Database::$transaction_lock === true && Database::$transaction_id === $id) {
            Database::$pdo->rollBack();
            Database::$transaction_lock = false;
            Database::$transaction_id++;
        }

        return $is_rollbacked;
    }
}
