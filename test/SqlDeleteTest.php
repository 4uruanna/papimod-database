<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\SqlDelete;
use Papimod\Database\sql\SqlTable;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlDelete::class)]
#[Medium]
final class SqlDeleteTest extends DatabaseTestCase
{
    private const TABLE = "sql_delete_test";

    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_delete_test (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            AGE INT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS sql_delete_test_2 (
            ID INT NOT NULL,
            FOO VARCHAR(255) NOT NULL,

            CONSTRAINT fk_sql_delete_test_2 FOREIGN KEY (ID) REFERENCES sql_delete_test(ID) ON DELETE CASCADE
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_delete_test_2;
        DROP TABLE IF EXISTS sql_delete_test;
    SQL;

    private const INSERT_QUERY = <<<SQL
        INSERT INTO sql_delete_test
            (NAME, AGE) 
        VALUES
            ('A', 1),
            ('B', 2),
            ('C', 3),
            ('D', 4),
            ('E', 5);

        INSERT INTO sql_delete_test_2
            (ID, FOO) 
        VALUES
            (1, 'FOO_A'),
            (2, 'FOO_B'),
            (3, 'FOO_C'),
            (4, 'FOO_D'),
            (5, 'FOO_E');
    SQL;

    private SqlDelete $delete;

    public function setUp(): void
    {
        parent::setUp();
        Database::pdo()->exec(self::DROP_QUERY);
        Database::pdo()->exec(self::SETUP_QUERY);
        Database::pdo()->exec(self::INSERT_QUERY);
        $this->delete = new SqlDelete(new SqlTable(self::TABLE));
    }

    public function testDelete(): void
    {
        $statement = $this->delete
            ->where("AGE")
            ->isGreater(2)
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(3, $statement->rowCount());
    }

    public function testJoinDelete(): void
    {
        $statement = $this->delete
            ->where("sql_delete_test_2.FOO")
            ->isIn(["FOO_A", "FOO_B"])
            ->innerJoin("sql_delete_test_2")
            ->on("sql_delete_test.ID", "sql_delete_test_2.ID")
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(2, $statement->rowCount());
    }
}
