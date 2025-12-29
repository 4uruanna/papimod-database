<?php

namespace Papimod\Database\Test\pdo;

use Papimod\Database\Database;
use Papimod\Database\pdo\StatementDropBuilder;
use Papimod\Database\StatementBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatementDropBuilder::class)]
final class StatementDropBuilderTest extends TestCase
{
    private string $table_name = "statement_drop_builder_test";

    public function setUp(): void
    {
        Database::pdo()->query("DROP TABLE IF EXISTS {$this->table_name}")->execute();
        Database::pdo()->query("CREATE TABLE IF NOT EXISTS {$this->table_name} (poo INT NOT NULL)")->execute();
    }

    public function testDropTable(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->drop()
            ->build();

        $this->assertTrue($statement->execute());
    }
}
