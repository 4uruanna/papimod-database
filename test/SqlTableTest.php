<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\SqlDrop;
use Papimod\Database\sql\SqlInsert;
use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\SqlTable;
use Papimod\Database\sql\SqlTruncate;
use Papimod\Database\sql\SqlUpdate;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlTable::class)]
#[Medium]
final class SqlTableTest extends DatabaseTableTestCase
{
    public function testDrop(): void
    {
        $this->assertEquals(9, Database::pdo()->query("SELECT * FROM test_c")->rowCount());
        $drop = $this->table_c->drop();
        $this->assertInstanceOf(SqlDrop::class, $drop);
        $statement = $drop->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->expectException(PDOException::class);
        Database::pdo()->query("SELECT * FROM test_c");
    }

    public function testTruncate(): void
    {
        $this->assertEquals(9, Database::pdo()->query("SELECT * FROM test_c")->rowCount());
        $truncate = $this->table_c->truncate();
        $this->assertInstanceOf(SqlTruncate::class, $truncate);
        $statement = $truncate->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(0, Database::pdo()->query("SELECT * FROM test_c")->rowCount());
    }

    public function testSelect(): void
    {
        $instance = $this->table_c->select();
        $this->assertInstanceOf(SqlSelect::class, $instance);
    }

    public function testInsert(): void
    {
        $instance = $this->table_c->insert();
        $this->assertInstanceOf(SqlInsert::class, $instance);
    }

    public function testUpdate(): void
    {
        $instance = $this->table_c->update();
        $this->assertInstanceOf(SqlUpdate::class, $instance);
    }
}
