<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\SqlTable;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlSelect::class)]
#[Medium]
final class SqlSelectTest extends DatabaseTableTestCase
{
    private SqlSelect $select;

    public function setUp(): void
    {
        parent::setUp();
        $this->select = $this->table_a->select();
    }

    public function testSelect(): void
    {
        $statement = $this->select->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(10, $statement->rowCount());
    }

    public function testSelectSingleColumn(): void
    {
        $this->select = $this->table_a->select(["t"]);
        $statement = $this->select->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(10, $statement->rowCount());
        $this->assertEquals(1, $statement->columnCount());
    }
}
