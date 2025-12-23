<?php

namespace Papimod\Database\Test;

use Dotenv\Dotenv;
use Papimod\Database\Database;
use Papimod\Database\DatabaseModule;
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
        DatabaseModule::configure();
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
