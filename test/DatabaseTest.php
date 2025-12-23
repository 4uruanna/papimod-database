<?php

namespace Papimod\Database\Test;

use Dotenv\Dotenv;
use Papimod\Database\Database;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;
use PHPUnit\Framework\TestCase;

#[CoversClass(Database::class)]
#[Medium]
final class DatabaseTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        defined("PAPI_DATABASE_HOST") || define("PAPI_DATABASE_HOST", $_ENV['DATABASE_HOST']);
        defined("PAPI_DATABASE_PORT") || define("PAPI_DATABASE_PORT", (int) ($_ENV['DATABASE_PORT']));
        defined("PAPI_DATABASE_USER") || define("PAPI_DATABASE_USER", $_ENV["DATABASE_USER"]);
        defined("PAPI_DATABASE_PASSWORD") || define("PAPI_DATABASE_PASSWORD", $_ENV["DATABASE_PASSWORD"]);
        defined("PAPI_DATABASE_CHARSET") || define("PAPI_DATABASE_CHARSET", $_ENV["DATABASE_CHARSET"]);
        defined("PAPI_DATABASE_NAME") || define("PAPI_DATABASE_NAME", $_ENV["DATABASE_NAME"]);
    }

    public function testConnection(): void
    {
        $pdo = Database::pdo();
        $this->assertInstanceOf(PDO::class, $pdo);
        $this->assertEquals($pdo, Database::pdo());
    }

    public function testCommitTransaction(): void
    {
        $transaction_id_a = Database::beginTransaction();
        $transaction_id_b = Database::beginTransaction();
        $this->assertFalse(Database::commit($transaction_id_b));
        $this->assertTrue(Database::commit($transaction_id_a));
        $this->assertFalse(Database::commit($transaction_id_b));
        $this->assertFalse(Database::commit($transaction_id_a));
    }

    public function testRollbackTransaction(): void
    {
        $transaction_id_a = Database::beginTransaction();
        $transaction_id_b = Database::beginTransaction();
        $this->assertFalse(Database::rollback($transaction_id_b));
        $this->assertTrue(Database::rollback($transaction_id_a));
        $this->assertFalse(Database::rollback($transaction_id_b));
        $this->assertFalse(Database::rollback($transaction_id_a));
    }
}
