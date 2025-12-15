<?php

namespace Papimod\Database\Test;

use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\trait\SqlJoin;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlJoin::class)]
#[Medium]
final class SqlJoinTest extends DatabaseTableTestCase
{
    private SqlSelect $select;

    public function setUp(): void
    {
        parent::setUp();
        $this->select = $this->table_a->select();
    }

    public function testInnerJoin(): void
    {
        $statement = $this->select
            ->innerJoin("test_b")
            ->on("test_a.id", "test_b.a_id")
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(7, count($result_list));
    }

    public function testLeftJoin(): void
    {
        $statement = $this->select
            ->leftJoin("test_b")
            ->on("test_a.id", "test_b.a_id")
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(13, count($result_list));
    }

    public function testRightJoin(): void
    {
        $statement = $this->select
            ->rightJoin("test_b")
            ->on("test_a.id", "test_b.a_id")
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(7, count($result_list));
    }

    public function testMultipleJoin(): void
    {
        $statement = $this->select
            ->innerJoin("test_b")
            ->on("test_a.id", "test_b.a_id")
            ->rightJoin("test_c")
            ->on("test_a.id", "test_c.a_id")
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(12, count($result_list));
    }
}
