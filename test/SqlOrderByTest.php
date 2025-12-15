<?php

namespace Papimod\Database\Test;

use Papimod\Database\sql\enumerator\Order;
use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\trait\SqlOrderBy;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlOrderBy::class)]
#[Medium]
final class SqlOrderByTest extends DatabaseTableTestCase
{
    private SqlSelect $select;

    public function setUp(): void
    {
        parent::setUp();
        $this->select = $this->table_a->select();
    }

    public function testOrderBy(): void
    {
        $statement = $this->select->orderBy("i", Order::DESC)->build();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(90, $result_list[0]["i"]);
    }

    public function testMultipleOrderBy(): void
    {
        $statement = $this->select
            ->orderBy("i", Order::DESC)
            ->orderBy("t", Order::ASC)
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result = $statement->fetchAll();
        $this->assertEquals("AC", $result[8]["t"]);
        $this->assertEquals("AB", $result[7]["t"]);
    }
}
