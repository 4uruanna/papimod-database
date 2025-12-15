<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(Database::class)]
#[Medium]
final class DatabaseTest extends DatabaseTestCase
{
    public function testDatabaseConnection(): void
    {
        $this->assertInstanceOf(PDO::class, Database::pdo());
    }

    public function testCommitTransaction(): void
    {
        $transaction_id_a = Database::beginTransaction();
        $transaction_id_b = Database::beginTransaction();
        $this->assertFalse(Database::commit($transaction_id_b));
        $this->assertTrue(Database::commit($transaction_id_a));
    }

    public function testRollbackTransaction(): void
    {
        $transaction_id_a = Database::beginTransaction();
        $transaction_id_b = Database::beginTransaction();
        $this->assertFalse(Database::rollback($transaction_id_b));
        $this->assertTrue(Database::rollback($transaction_id_a));
    }

    public function testPreventReUseTransaction(): void
    {
        $transaction_id = Database::beginTransaction();
        $this->assertTrue(Database::commit($transaction_id));
        $this->assertFalse(Database::commit($transaction_id));

        $transaction_id = Database::beginTransaction();
        $this->assertTrue(Database::rollback($transaction_id));
        $this->assertFalse(Database::rollback($transaction_id));

        $transaction_id = Database::beginTransaction();
        $this->assertTrue(Database::commit($transaction_id));
        $this->assertFalse(Database::rollback($transaction_id));

        $transaction_id = Database::beginTransaction();
        $this->assertTrue(Database::rollback($transaction_id));
        $this->assertFalse(Database::commit($transaction_id));
    }
}
