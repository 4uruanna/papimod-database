<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    public function createTable($name, ...$column)
    {
        $pdo = Database::pdo();

        $query = <<<SQL
            CREATE TABLE IF NOT EXISTS $name (
        SQL;

        $$pdo->exec();
    }
}
