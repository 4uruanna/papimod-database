<?php

namespace Papimod\Database\Test\pdo\trait;

use Papimod\Database\Database;
use Papimod\Database\pdo\enumerator\Type;
use Papimod\Database\pdo\trait\SqlWhere;
use Papimod\Database\StatementBuilder;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SqlWhere::class)]
final class SqlWhereTest extends TestCase
{
    private string $table_name = "database_where_test";

    public function setUp(): void
    {
        $pdo = Database::pdo();
        $pdo->query("DROP TABLE IF EXISTS {$this->table_name}");
        $pdo->query(<<<SQL
            CREATE TABLE IF NOT EXISTS {$this->table_name}
            (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `string` TEXT,
                `decimal` DECIMAL(20, 10),
                `date_time` DATETIME,
                PRIMARY KEY (`id`)
            )
        SQL);

        $pdo->query(<<<SQL
            INSERT INTO {$this->table_name}
            (`string`, `decimal`, `date_time`)
            VALUES
            ("a", 2.3, "2020-12-12 18:55:23"),
            ("b", 2.4, "2020-12-12 20:55:23"),
            ("c", 2.5, NULL)
        SQL);
    }

    public function testIsEqual(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("string")->isEqual("a")
            ->build();

        $statement->execute();
        $this->assertEquals(2.3, $statement->fetch(PDO::FETCH_ASSOC)["decimal"]);
    }

    public function testIsNotEqual(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("string")->isNotEqual("a")
            ->build();

        $statement->execute();
        $this->assertEquals(2, $statement->rowCount());
    }

    public function testIsGreater(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("decimal")->isGreater(2.4, Type::DECIMAL)
            ->build();

        $statement->execute();
        $this->assertEquals("c", $statement->fetch(PDO::FETCH_ASSOC)["string"]);
    }

    public function testIsGreaterOrEqual(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("decimal")->isGreaterOrEqual(2.4, Type::DECIMAL)
            ->build();

        $statement->execute();
        $this->assertEquals(2, $statement->rowCount());
    }

    public function testIsLess(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("decimal")->isLess(2.4, Type::DECIMAL)
            ->build();

        $statement->execute();
        $this->assertEquals("a", $statement->fetch(PDO::FETCH_ASSOC)["string"]);
    }

    public function testIsLessOrEqual(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("decimal")->isLessOrEqual(2.4, Type::DECIMAL)
            ->build();

        $statement->execute();
        $this->assertEquals(2, $statement->rowCount());
    }

    public function testIsLike(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("string")->isLike("a%")
            ->build();

        $statement->execute();
        $this->assertCount(1, $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testIsNotLike(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("string")->isNotLike("a%")
            ->build();

        $statement->execute();
        $this->assertCount(2, $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testIsNull(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("date_time")->isNull()
            ->build();

        $statement->execute();
        $this->assertEquals("c", $statement->fetch(PDO::FETCH_ASSOC)["string"]);
    }

    public function testIsNotNull(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("date_time")->isNotNull()
            ->build();

        $statement->execute();
        $this->assertCount(2, $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testIsIn(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("decimal")->isIn([2.3, 2.4])
            ->build();

        $statement->execute();
        $this->assertCount(2, $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testIsNotIn(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("decimal")->isNotIn([2.3, 2.4], Type::DOUBLE)
            ->build();

        $statement->execute();
        $this->assertEquals("c", $statement->fetch(PDO::FETCH_ASSOC)["string"]);
    }

    public function testAnd(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("decimal")->isNotEqual(2.3, Type::DOUBLE)
            ->and("string")->isNotEqual("b")
            ->build();

        $statement->execute();
        $this->assertEquals("c", $statement->fetch(PDO::FETCH_ASSOC)["string"]);
    }

    public function testOr(): void
    {
        $statement = StatementBuilder::from($this->table_name)
            ->select()
            ->where("string")->isEqual("a", Type::TEXT)
            ->or("date_time")->isNull()
            ->build();

        $statement->execute();
        $this->assertCount(2, $statement->fetchAll(PDO::FETCH_ASSOC));
    }
}
