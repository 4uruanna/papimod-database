<?php

namespace Papimod\Database\Test;

use Papimod\Database\sql\SqlDelete;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlDelete::class)]
#[Medium]
final class SqlDeleteTest extends DatabaseTableTestCase
{
    private SqlDelete $delete;

    public function setUp(): void
    {
        parent::setUp();
        $this->delete = $this->table_a->delete();
    }

    public function testDelete(): void
    {
        $statement = $this->delete
            ->where("i")
            ->isGreater(20)
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(7, $statement->rowCount());
    }

    public function testJoinDelete(): void
    {
        $statement = $this->delete
            ->where("test_b.t")
            ->isIn(["BA", "BB", "BE", "BF"])
            ->innerJoin("test_b")
            ->on("test_a.id", "test_b.a_id")
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(4, $statement->rowCount());
    }
}
