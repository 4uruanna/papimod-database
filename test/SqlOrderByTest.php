<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\model\Order;
use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\SqlTable;
use Papimod\Database\sql\trait\SqlOrderBy;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlOrderBy::class)]
#[Medium]
final class SqlOrderByTest extends DatabaseTestCase
{
    private const TABLE = "sql_order_test";

    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_order_test (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            AGE INT NOT NULL
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_order_test;
    SQL;

    private const INSERT_QUERY = <<<SQL
        INSERT INTO sql_order_test
            (NAME, AGE) 
        VALUES
            ('A', 1),
            ('B', 2),
            ('C', 3),
            ('D', 4),
            ('E', 5),
            ('F', 1);
    SQL;

    private SqlSelect $select;

    public function setUp(): void
    {
        parent::setUp();
        Database::pdo()->exec(self::DROP_QUERY);
        Database::pdo()->exec(self::SETUP_QUERY);
        Database::pdo()->exec(self::INSERT_QUERY);
        $this->select = new SqlSelect(new SqlTable(self::TABLE));
    }

    public function testOrderBy(): void
    {
        $statement = $this->select->orderBy("AGE", Order::DESC)->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(6, count($result_list));
        $this->assertEquals(5, $result_list[0]["AGE"]);
    }

    public function testMultipleOrderBy(): void
    {
        $statement = $this->select
            ->orderBy("AGE", Order::ASC)
            ->orderBy("NAME", Order::DESC)
            ->fetch();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result = $statement->fetch();
        $this->assertEquals("F", $result["NAME"]);
    }
}
