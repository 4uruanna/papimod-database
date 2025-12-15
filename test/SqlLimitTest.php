<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\SqlTable;
use Papimod\Database\sql\trait\SqlLimit;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlLimit::class)]
#[Medium]
final class SqlLimitTest extends DatabaseTestCase
{
    private const TABLE = "sql_limit_test";

    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_limit_test (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            AGE INT NOT NULL
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_limit_test;
    SQL;

    private const INSERT_QUERY = <<<SQL
        INSERT INTO sql_limit_test
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

    public function testLimit(): void
    {
        $statement = $this->select->limit(2)->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(2, $statement->rowCount());
    }

    public function testIgnoreNegativeAndZeroLimit(): void
    {
        $statement = $this->select->limit(-1)->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(5, $statement->rowCount());

        $statement = $this->select->limit(0)->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(5, $statement->rowCount());
    }

    public function testOffset(): void
    {
        $statement = $this->select->limit(10, 1)->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(4, $statement->rowCount());
    }

    public function testIgnoreNegativeAndZeroOffset(): void
    {
        $statement = $this->select->limit(10, -1)->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(5, $statement->rowCount());

        $statement = $this->select->limit(10, 0)->fetch();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(5, $statement->rowCount());
    }
}
