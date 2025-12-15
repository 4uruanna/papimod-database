<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\enumerator\Type;
use Papimod\Database\sql\model\Column;
use Papimod\Database\sql\SqlTable;
use Papimod\Database\sql\SqlUpdate;
use PDOStatement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;

#[CoversClass(SqlUpdate::class)]
#[Medium]
final class SqlUpdateTest extends DatabaseTestCase
{
    private const TABLE = "sql_update_test";

    private const SETUP_QUERY = <<<SQL
        CREATE TABLE IF NOT EXISTS sql_update_test (
            ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            AGE INT
        );
    SQL;

    private const DROP_QUERY = <<<SQL
        DROP TABLE IF EXISTS sql_update_test;
    SQL;

    private const INSERT_QUERY = <<<SQL
        INSERT INTO sql_update_test
            (NAME, AGE) 
        VALUES
            ('A', 1),
            ('B', 2)
    SQL;

    private SqlUpdate $update;

    public function setUp(): void
    {
        parent::setUp();
        Database::pdo()->exec(self::DROP_QUERY);
        Database::pdo()->exec(self::SETUP_QUERY);
        Database::pdo()->exec(self::INSERT_QUERY);
        $this->update = new SqlUpdate(new SqlTable(self::TABLE), Column::STRING("NAME"), Column::INT("AGE"));
    }

    public function testUpdateValues(): void
    {
        $statement = $this->update
            ->set(
                (object) array(
                    "NAME" => "Foo",
                    "AGE" => 1
                )
            )
            ->where("ID")
            ->isEqual(1, Type::INTEGER)
            ->build();

        $this->assertInstanceOf(PDOStatement::class, $statement);
        $statement->execute();
        $this->assertEquals(1, $statement->rowCount());
    }
}
