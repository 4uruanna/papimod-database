<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\model\Column;
use Papimod\Database\sql\SqlInsert;
use Papimod\Database\sql\SqlTable;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlInsert::class)]
#[Medium]
final class SqlInsertTest extends DatabaseTestCase
{
    private const TABLE = "sql_insert_test";

    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_insert_test (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            AGE INT
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_insert_test;
    SQL;

    private SqlInsert $insert;

    public function setUp(): void
    {
        parent::setUp();
        Database::pdo()->exec(self::DROP_QUERY);
        Database::pdo()->exec(self::SETUP_QUERY);
        $this->insert = new SqlInsert(new SqlTable(self::TABLE), Column::STRING("NAME"), Column::INT("AGE"));
    }

    public function testInsertValues(): void
    {
        $statement = $this->insert
            ->value((object) array("NAME" => "Foo", "AGE" => 1))
            ->value((object) array("NAME" => "Bar", "AGE" => 3))
            ->value((object) array("NAME" => "Poo", "AGE" => null))
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(3, $statement->rowCount());
    }
}
