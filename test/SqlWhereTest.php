<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\SqlTable;
use Papimod\Database\sql\trait\SqlWhere;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlWhere::class)]
#[Medium]
final class SqlWhereTest extends DatabaseTestCase
{
    private const TABLE = "sql_where_test";

    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_where_test (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            AGE INT NOT NULL,
            MAYBE VARCHAR(255)
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_where_test;
    SQL;

    private const INSERT_QUERY = <<<SQL
        INSERT INTO sql_where_test
            (NAME, AGE, MAYBE) 
        VALUES
            ('AAA', 1, NULL),
            ('BAB', 2, NULL),
            ('CCC', 2, "YES"),
            ('DAD', 3, "NO"),
            ('EEE', 3, NULL);
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

    public function testIsEqual(): void
    {
        $statement = $this->select
            ->where("NAME")
            ->isEqual("AAA")
            ->or("AGE")
            ->isEqual(3)
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(3, count($result_list));
    }

    public function testIsNotEqual(): void
    {
        $statement = $this->select
            ->where("NAME")
            ->isNotEqual("AAA")
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(4, count($result_list));
    }

    public function testIsNull(): void
    {
        $statement = $this->select
            ->where("MAYBE")
            ->isNull()
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(3, count($result_list));
    }

    public function testIsNotNull(): void
    {
        $statement = $this->select
            ->where("MAYBE")
            ->isNotNull()
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(2, count($result_list));
    }

    public function testIsGreater(): void
    {
        $statement = $this->select
            ->where("AGE")
            ->isGreater(2)
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(2, count($result_list));
    }

    public function testIsLess(): void
    {
        $statement = $this->select
            ->where("AGE")
            ->isLess(2)
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(1, count($result_list));
    }

    public function testIsGreaterOrEqual(): void
    {
        $statement = $this->select
            ->where("AGE")
            ->isGreaterOrEqual(2)
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(4, count($result_list));
    }

    public function testIsLessOrEqual(): void
    {
        $statement = $this->select
            ->where("AGE")
            ->isLessOrEqual(2)
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(3, count($result_list));
    }

    public function testIsIn(): void
    {
        $statement = $this->select
            ->where("AGE")
            ->isIn([1, 3])
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(3, count($result_list));
    }

    public function testIsNotIn(): void
    {
        $statement = $this->select
            ->where("AGE")
            ->isNotIn([1, 3])
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(2, count($result_list));
    }

    public function testIsLike(): void
    {
        $statement = $this->select
            ->where("NAME")
            ->isLike("A%")
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(1, count($result_list));
    }

    public function testIsNotLike(): void
    {
        $statement = $this->select
            ->where("NAME")
            ->isNotLike("%A%")
            ->build();

        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(2, count($result_list));
    }
}
