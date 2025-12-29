<?php

namespace Papimod\Database\Test\pdo;

use Papimod\Database\Database;
use Papimod\Database\pdo\StatementDropBuilder;
use Papimod\Database\StatementBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatementDropBuilder::class)]
final class StatementTruncateBuilderTest extends TestCase
{
    private string $table_name = "statement_truncate_builder_test";

    public function setUp(): void
    {
        Database::pdo()->query("CREATE TABLE IF NOT EXISTS {$this->table_name} (poo INT NOT NULL)")->execute();
        Database::pdo()->query("INSERT INTO {$this->table_name} (poo) VALUES (1), (2), (3);");
    }

    public function testDropTable(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->truncate()
            ->build()
            ->execute();

        $statement = Database::pdo()->query("SELECT * FROM {$this->table_name}");
        $statement->execute();
        $this->assertEquals(0, $statement->rowCount());
    }
}
