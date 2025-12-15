<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\SqlTable;
use Papimod\Database\sql\trait\SqlJoin;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlJoin::class)]
#[Medium]
final class SqlJoinTest extends DatabaseTestCase
{
    private const TABLE = "sql_join_test_a a";

    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_join_test_a (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            AGE INT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS sql_join_test_b (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            A_AGE INT NOT NULL, 
            FOO VARCHAR(255) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS sql_join_test_c (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            B_ID INT NOT NULL, 
            BAR VARCHAR(255) NOT NULL
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_join_test_a;
        DROP TABLE IF EXISTS sql_join_test_b;
        DROP TABLE IF EXISTS sql_join_test_c;
    SQL;

    private const INSERT_QUERY = <<<SQL
        INSERT INTO sql_join_test_a
            (NAME, AGE) 
        VALUES
            ('A', 1),
            ('B', 2),
            ('C', 3);

        INSERT INTO sql_join_test_b
            (A_AGE, FOO) 
        VALUES
            (1, 'FOO_A'),
            (2, 'FOO_B'),
            (10, 'FOO_C'),
            (11, 'FOO_D');

        INSERT INTO sql_join_test_c
            (B_ID, BAR) 
        VALUES
            (1, 'BAR_AA'),
            (10, 'BAR_AB'),
            (11, 'BAR_BA');
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

    public function testInnerJoin(): void
    {
        $statement = $this->select
            ->innerJoin("sql_join_test_b b")
            ->on("a.AGE", "b.A_AGE")
            ->fetch();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(2, count($result_list));
        $this->assertEquals("FOO_A", $result_list[0]["FOO"]);
        $this->assertEquals("FOO_B", $result_list[1]["FOO"]);
    }

    public function testLeftJoin(): void
    {
        $statement = $this->select
            ->leftJoin("sql_join_test_b b")
            ->on("a.ID", "b.A_AGE")
            ->fetch();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(3, count($result_list));
    }

    public function testRightJoin(): void
    {
        $statement = $this->select
            ->rightJoin("sql_join_test_b b")
            ->on("a.ID", "b.A_AGE")
            ->fetch();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(4, count($result_list));
    }

    public function testMultipleJoin(): void
    {
        $statement = $this->select
            ->innerJoin("sql_join_test_b b")
            ->on("a.AGE", "b.A_AGE")
            ->innerJoin("sql_join_test_c c")
            ->on("b.ID", "c.B_ID")
            ->fetch();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $result_list = $statement->fetchAll();
        $this->assertEquals(1, count($result_list));
    }
}
