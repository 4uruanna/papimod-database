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
final class SqlSelectTest extends DatabaseTestCase
{
    private const TABLE = "sql_select_test";

    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_select_test (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            AGE INT NOT NULL
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_select_test;
    SQL;

    private const INSERT_QUERY = <<<SQL
        INSERT INTO sql_select_test
            (NAME, AGE) 
        VALUES
            ('A', 1),
            ('B', 2),
            ('C', 3),
            ('D', 4),
            ('E', 5);
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

    public function testSelect(): void
    {
        $statement = $this->select->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(5, $statement->rowCount());
    }

    public function testSelectSingleColumn(): void
    {
        $this->select = new SqlSelect(new SqlTable(self::TABLE), ["NAME"]);
        $statement = $this->select->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(1, $statement->columnCount());
    }
}
