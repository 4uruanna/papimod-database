<?php

namespace Papimod\Database\Test;

use Papimod\Database\sql\model\Column;
use Papimod\Database\sql\SqlInsert;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlInsert::class)]
#[Medium]
final class SqlInsertTest extends DatabaseTableTestCase
{
    private SqlInsert $insert;

    public function setUp(): void
    {
        parent::setUp();
        $this->insert = $this->table_a->insert(
            Column::STRING("t"),
            Column::INT("i"),
            Column::INT("n")
        );
    }

    public function testInsertValues(): void
    {
        $statement = $this->insert
            ->value((object) array("t" => "Foo", "i" => 1, "n" => null))
            ->value((object) array("t" => "Bar", "i" => 3, "n" => "ok"))
            ->value((object) array("t" => "Poo", "i" => 10, "n" => null))
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(3, $statement->rowCount());
    }
}
