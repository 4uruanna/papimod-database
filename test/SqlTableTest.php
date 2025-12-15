<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\SqlSelect;
use Papimod\Database\sql\SqlTable;
use Papimod\Database\Test\DatabaseTestCase;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlTable::class)]
#[Medium]
final class SqlTableTest extends DatabaseTestCase
{
    private const TABLE = 'sql_table_test';
    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_table_test (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255),
            AGE INT(3) NOT NULL
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_table_test;
    SQL;

    private const INSERT_QUERY = <<<SQL
        INSERT INTO sql_table_test
            (NAME, AGE) 
        VALUES
            ('A', 1),
            ('B', 2),
            ('C', 3),
            ('D', 4),
            ('E', 5);
    SQL;

    private SqlTable $table;

    public function setUp(): void
    {
        parent::setUp();
        Database::pdo()->exec(self::DROP_QUERY);
        Database::pdo()->exec(self::SETUP_QUERY);
        Database::pdo()->exec(self::INSERT_QUERY);
        $this->table = new SqlTable(self::TABLE);
    }

    public function testDrop(): void
    {
        $this->assertEquals(5, Database::pdo()->query("SELECT * FROM " . self::TABLE)->rowCount());
        $statement = $this->table->drop();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->expectException(PDOException::class);
        Database::pdo()->query("SELECT * FROM " . self::TABLE);
    }

    public function testTruncate(): void
    {
        $this->assertEquals(5, Database::pdo()->query("SELECT * FROM " . self::TABLE)->rowCount());
        $statement = $this->table->truncate();
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(0, Database::pdo()->query("SELECT * FROM " . self::TABLE)->rowCount());
    }

    public function testSelect(): void
    {
        $instance = $this->table->select();
        $this->assertInstanceOf(SqlSelect::class, $instance);
    }
}
