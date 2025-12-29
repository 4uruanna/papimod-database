<?php

namespace Papimod\Database\Test\pdo;

use Faker\Factory;
use Faker\Generator;
use Papimod\Database\Database;
use Papimod\Database\pdo\enumerator\Type;
use Papimod\Database\pdo\model\Column;
use Papimod\Database\pdo\StatementInsertBuilder;
use Papimod\Database\StatementBuilder;
use PDO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatementInsertBuilder::class)]
final class StatementInsertBuilderTest extends TestCase
{
    private static Generator $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    private string $table_name = "statement_insert_builder_test";

    public function setUp(): void
    {
        Database::pdo()->query("DROP TABLE IF EXISTS {$this->table_name}");
        Database::pdo()->query(<<<SQL
            CREATE TABLE IF NOT EXISTS {$this->table_name}
            (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `string` TEXT,
                `decimal` DECIMAL(20, 10),
                `date` DATETIME,
                `blob` BLOB,
                PRIMARY KEY (`id`)
            )
        SQL);
    }

    public function testInsertText(): void
    {
        $sample = self::$faker->text(255);

        StatementBuilder::from($this->table_name)
            ->insert(new Column("string", $sample, Type::TEXT))
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($sample, $statement->fetch(PDO::FETCH_ASSOC)["string"]);
    }

    public function testInsertDecimal()
    {
        $sample = self::$faker->randomFloat(6, 1, 2);

        $s = StatementBuilder::from($this->table_name)
            ->insert(new Column("decimal", $sample, Type::DOUBLE))
            ->build()
            ->execute();

        $statement = Database::pdo()->prepare("SELECT * FROM {$this->table_name} WHERE ID = 1");
        $statement->execute();
        $this->assertEquals($sample, $statement->fetch(PDO::FETCH_ASSOC)["decimal"]);
    }
}
