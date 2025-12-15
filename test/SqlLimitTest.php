<?php

namespace Papimod\Database\Test;

use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\trait\SqlLimit;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlLimit::class)]
#[Medium]
final class SqlLimitTest extends DatabaseTableTestCase
{
    private SqlSelect $select;

    public function setUp(): void
    {
        parent::setUp();
        $this->select = $this->table_a->select();
    }

    public function testLimit(): void
    {
        $statement = $this->select->limit(2)->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(2, $statement->rowCount());
    }

    public function testIgnoreNegativeAndZeroLimit(): void
    {
        $statement = $this->select->limit(-1)->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(10, $statement->rowCount());

        $statement = $this->select->limit(0)->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(10, $statement->rowCount());
    }

    public function testOffset(): void
    {
        $statement = $this->select->limit(0, 1)->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(9, $statement->rowCount());
    }

    public function testIgnoreNegativeAndZeroOffset(): void
    {
        $statement = $this->select->limit(0, -1)->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(10, $statement->rowCount());

        $statement = $this->select->limit(0, 0)->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(10, $statement->rowCount());
    }
}
